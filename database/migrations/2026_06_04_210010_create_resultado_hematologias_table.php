<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_hematologias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');

            // Pestaña General — Hemograma CBC
            $table->decimal('wbc', 8, 3)->nullable();
            $table->decimal('lymph_abs', 8, 3)->nullable();
            $table->decimal('mid_abs', 8, 3)->nullable();
            $table->decimal('gran_abs', 8, 3)->nullable();
            $table->decimal('lymph_pct', 5, 2)->nullable();
            $table->decimal('mid_pct', 5, 2)->nullable();
            $table->decimal('gran_pct', 5, 2)->nullable();
            $table->decimal('rbc', 8, 3)->nullable();
            $table->decimal('hgb', 5, 2)->nullable();
            $table->decimal('hct', 5, 2)->nullable();
            $table->decimal('mcv', 6, 2)->nullable();
            $table->decimal('mch', 5, 2)->nullable();
            $table->decimal('mchc', 5, 2)->nullable();
            $table->decimal('rdw_cv', 5, 2)->nullable();
            $table->decimal('rdw_sd', 5, 2)->nullable();
            $table->decimal('plt', 8, 2)->nullable();
            $table->decimal('mpv', 5, 2)->nullable();
            $table->decimal('pdw', 5, 2)->nullable();
            $table->decimal('pct', 6, 3)->nullable();
            $table->decimal('plcr', 5, 2)->nullable();
            $table->decimal('vitamina_b12', 8, 2)->nullable();
            $table->decimal('acido_folico', 8, 2)->nullable();
            $table->decimal('hierro', 8, 2)->nullable();
            $table->text('observacion_general')->nullable();

            // Pestaña Hemograma completo (manual)
            $table->decimal('hemoglobina_gdl', 5, 2)->nullable();
            $table->decimal('hemoglobina_pct', 5, 2)->nullable();
            $table->decimal('hematocrito_pct', 5, 2)->nullable();
            $table->string('eritrocitos', 20)->nullable();
            $table->string('leucocitos', 20)->nullable();
            // Índices hematícos
            $table->decimal('vcm', 6, 2)->nullable();
            $table->decimal('hcm', 6, 2)->nullable();
            $table->decimal('chcm', 6, 2)->nullable();
            // Recuento diferencial (%)
            $table->decimal('mieloblastos', 5, 2)->nullable();
            $table->decimal('promielocitos', 5, 2)->nullable();
            $table->decimal('mielocitos', 5, 2)->nullable();
            $table->decimal('metamielocitos', 5, 2)->nullable();
            $table->decimal('bandas', 5, 2)->nullable();
            $table->decimal('segmentos', 5, 2)->nullable();
            $table->decimal('linfocitos', 5, 2)->nullable();
            $table->decimal('monocitos', 5, 2)->nullable();
            $table->decimal('eosinofilos', 5, 2)->nullable();
            $table->decimal('basofilos', 5, 2)->nullable();
            // Observaciones morfológicas
            $table->string('hipocromia')->nullable();
            $table->string('poiquilocitosis')->nullable();
            $table->string('anisocitosis')->nullable();
            $table->string('cls_en_diana')->nullable();
            $table->string('macrocitosis')->nullable();
            $table->string('cls_crenadas')->nullable();
            $table->string('microcitosis')->nullable();
            $table->string('macroplaquet')->nullable();
            // Determinaciones
            $table->string('eritrosedimentacion')->nullable();
            $table->string('conteo_eosinofilos')->nullable();
            $table->string('conteo_plaquetas')->nullable();
            $table->string('conteo_reticulocitos')->nullable();
            $table->string('reticulocitos_corregidos')->nullable();
            $table->string('inv_falcemia')->nullable();
            $table->string('inv_celulas_le')->nullable();
            $table->string('inv_hematozoarios')->nullable();
            $table->text('observacion_hemograma')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_hematologias');
    }
};
