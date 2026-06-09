<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_serologias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');
            $table->boolean('reportar')->default(false);

            $table->string('salmonella_o_a')->nullable();
            $table->string('salmonella_o_b')->nullable();
            $table->string('salmonella_o_c')->nullable();
            $table->string('salmonella_o_d')->nullable();
            $table->string('salmonella_h_a')->nullable();
            $table->string('salmonella_h_b')->nullable();
            $table->string('salmonella_h_c')->nullable();
            $table->string('salmonella_h_d')->nullable();
            $table->string('proteus_ox2')->nullable();
            $table->string('proteus_ox19')->nullable();
            $table->string('proteus_oxk')->nullable();
            $table->string('brucella_abortus')->nullable();
            $table->string('typhoide_o_somatica')->nullable();
            $table->text('observacion')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_serologias');
    }
};
