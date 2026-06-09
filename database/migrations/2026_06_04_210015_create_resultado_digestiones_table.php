<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_digestiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');

            // Físico-Químico
            $table->string('color')->nullable();
            $table->string('olor')->nullable();
            $table->string('consistencia')->nullable();
            $table->string('alimentos_no_digeridos')->nullable();
            $table->string('mucus')->nullable();
            $table->string('reaccion_ph')->nullable();
            $table->string('sangre_oculta')->nullable();
            $table->string('grasas')->nullable();
            $table->string('sustancia_reductora')->nullable();
            $table->string('tripsina')->nullable();

            // Examen Microscópico
            $table->string('leucocitos')->nullable();
            $table->string('eritrocitos')->nullable();
            $table->string('celulas_epiteliales')->nullable();
            $table->string('fibras_mucosas')->nullable();
            $table->string('cristales')->nullable();
            $table->string('bacterias')->nullable();
            $table->string('huevos')->nullable();
            $table->string('parasitos')->nullable();
            $table->string('quistes')->nullable();
            $table->string('granulos')->nullable();
            $table->string('larvas')->nullable();
            $table->string('materiales_extranos')->nullable();
            $table->text('observacion')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_digestiones');
    }
};
