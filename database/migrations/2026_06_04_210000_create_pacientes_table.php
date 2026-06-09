<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('cedula', 20)->nullable()->unique();
            $table->unsignedTinyInteger('edad')->nullable();
            $table->enum('sexo', ['F', 'M'])->nullable();
            $table->enum('nacionalidad', ['dominicana', 'haitiana', 'otra'])->default('dominicana');
            $table->string('medico_tratante')->nullable();
            $table->string('seguro_medico')->nullable();
            $table->string('cuenta')->nullable();
            $table->foreignId('laboratorio_id')->constrained('laboratorios');
            $table->foreignId('creado_por')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
