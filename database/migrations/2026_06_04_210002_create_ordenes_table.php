<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden', 20)->unique();
            $table->string('numero_entrada', 20)->nullable();
            $table->string('numero_factura', 20)->nullable();
            $table->enum('tipo_paciente', ['ambulatorio', 'internado'])->default('ambulatorio');
            $table->date('fecha_entrada');
            $table->boolean('embarazada')->default(false);
            $table->enum('estado', ['pendiente', 'en_proceso', 'listo', 'por_validar', 'validado'])->default('pendiente');
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('laboratorio_id')->constrained('laboratorios');
            $table->foreignId('creado_por')->constrained('users');
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orden_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes')->cascadeOnDelete();
            $table->foreignId('analisis_tipo_id')->constrained('analisis_tipos');
            $table->enum('estado', ['pendiente', 'en_proceso', 'listo'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_analisis');
        Schema::dropIfExists('ordenes');
    }
};
