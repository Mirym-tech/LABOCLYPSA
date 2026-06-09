<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Uroanálisis
        Schema::create('resultado_uroanalis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');

            // Físico-Químico
            $table->string('color')->nullable();
            $table->string('aspecto')->nullable();
            $table->string('densidad')->nullable();
            $table->string('ph')->nullable();
            $table->string('glucosa')->nullable();
            $table->string('proteina')->nullable();
            $table->string('acetona')->nullable();
            $table->string('bilirrubina')->nullable();
            $table->string('urobilinogeno')->nullable();
            $table->string('sangre_oculta')->nullable();
            $table->string('hemoglobina')->nullable();
            $table->string('nitrito')->nullable();

            // Segmento Urinario
            $table->string('leucocitos')->nullable();
            $table->string('eritrocitos')->nullable();
            $table->string('celulas_epiteliales')->nullable();
            $table->string('celulas_renales')->nullable();
            $table->string('bacterias')->nullable();
            $table->string('fibras_mucosas')->nullable();
            $table->string('cristales')->nullable();
            $table->string('cilindros')->nullable();
            $table->string('levaduras')->nullable();
            $table->string('t_vaginalis')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });

        // Coprológico
        Schema::create('resultado_coprologicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');

            $table->string('tipo_estudio')->nullable();
            $table->string('color')->nullable();
            $table->string('consistencia')->nullable();
            $table->boolean('sin_parasitos')->default(false);
            $table->text('se_observan')->nullable();
            $table->string('sangre_oculta')->nullable();
            $table->text('invest_amebas')->nullable();
            $table->text('observacion')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_coprologicos');
        Schema::dropIfExists('resultado_uroanalis');
    }
};
