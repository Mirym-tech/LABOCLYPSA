<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * En PostgreSQL, foreignId()->constrained() crea la FK constraint pero NO el índice
 * en la columna referenciante. Este migration agrega los índices faltantes.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── pacientes ─────────────────────────────────────────────────────────
        Schema::table('pacientes', function (Blueprint $table) {
            $table->index('laboratorio_id',  'idx_pacientes_laboratorio');
            $table->index('creado_por',      'idx_pacientes_creado_por');
            $table->index('nombre',          'idx_pacientes_nombre');
            $table->index('created_at',      'idx_pacientes_created_at');
            // cedula y codigo ya tienen unique() que implica índice
        });

        // ── ordenes ───────────────────────────────────────────────────────────
        Schema::table('ordenes', function (Blueprint $table) {
            $table->index('paciente_id',    'idx_ordenes_paciente');
            $table->index('laboratorio_id', 'idx_ordenes_laboratorio');
            $table->index('creado_por',     'idx_ordenes_creado_por');
            $table->index('estado',         'idx_ordenes_estado');
            $table->index('created_at',     'idx_ordenes_created_at');
            $table->index('fecha_entrada',  'idx_ordenes_fecha_entrada');
        });

        // ── orden_analisis ────────────────────────────────────────────────────
        Schema::table('orden_analisis', function (Blueprint $table) {
            $table->index('orden_id',         'idx_oa_orden');
            $table->index('analisis_tipo_id', 'idx_oa_tipo');
            $table->index('estado',           'idx_oa_estado');
        });

        // ── resultado_hematologias ────────────────────────────────────────────
        Schema::table('resultado_hematologias', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_rhem_oa');
            $table->index('bioanalista_id',    'idx_rhem_bio');
        });

        // ── resultado_bacteriologias ──────────────────────────────────────────
        Schema::table('resultado_bacteriologias', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_rbac_oa');
            $table->index('bioanalista_id',    'idx_rbac_bio');
        });

        // ── resultado_serologias ──────────────────────────────────────────────
        Schema::table('resultado_serologias', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_rser_oa');
            $table->index('bioanalista_id',    'idx_rser_bio');
        });

        // ── resultado_coleras ─────────────────────────────────────────────────
        Schema::table('resultado_coleras', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_rcol_oa');
            $table->index('bioanalista_id',    'idx_rcol_bio');
        });

        // ── resultado_uroanalis ───────────────────────────────────────────────
        Schema::table('resultado_uroanalis', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_ruro_oa');
            $table->index('bioanalista_id',    'idx_ruro_bio');
        });

        // ── resultado_coprologias ─────────────────────────────────────────────
        // La tabla se llama resultado_coprologias o resultado_uroanalis?
        // Revisamos si existe antes de agregar
        if (Schema::hasTable('resultado_coprologias')) {
            Schema::table('resultado_coprologias', function (Blueprint $table) {
                $table->index('orden_analisis_id', 'idx_rcop_oa');
            });
        }

        // ── resultado_digestiones ─────────────────────────────────────────────
        Schema::table('resultado_digestiones', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_rdig_oa');
            $table->index('bioanalista_id',    'idx_rdig_bio');
        });

        // ── resultado_varios ──────────────────────────────────────────────────
        Schema::table('resultado_varios', function (Blueprint $table) {
            $table->index('orden_analisis_id', 'idx_rvar_oa');
            $table->index('bioanalista_id',    'idx_rvar_bio');
        });

        // ── activity_log (Spatie) ─────────────────────────────────────────────
        // Spatie ya agrega algunos índices, verificamos causer_id y created_at
        Schema::table('activity_log', function (Blueprint $table) {
            // created_at ya existe en la migración de Spatie
            // causer_id podría no tener índice si la versión es antigua
            if (!$this->hasIndex('activity_log', 'activity_log_causer_id_causer_type_index')) {
                $table->index(['causer_id', 'causer_type'], 'idx_actlog_causer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pacientes',               fn ($t) => $t->dropIndex(['laboratorio_id', 'creado_por', 'nombre', 'created_at']));
        Schema::table('ordenes',                 fn ($t) => $t->dropIndex(['paciente_id', 'laboratorio_id', 'creado_por', 'estado', 'created_at', 'fecha_entrada']));
        Schema::table('orden_analisis',          fn ($t) => $t->dropIndex(['orden_id', 'analisis_tipo_id', 'estado']));
        Schema::table('resultado_hematologias',  fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
        Schema::table('resultado_bacteriologias',fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
        Schema::table('resultado_serologias',    fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
        Schema::table('resultado_coleras',       fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
        Schema::table('resultado_uroanalis',     fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
        Schema::table('resultado_digestiones',   fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
        Schema::table('resultado_varios',        fn ($t) => $t->dropIndex(['orden_analisis_id', 'bioanalista_id']));
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        return collect(\DB::select("SELECT indexname FROM pg_indexes WHERE tablename = ?", [$table]))
            ->pluck('indexname')
            ->contains($indexName);
    }
};
