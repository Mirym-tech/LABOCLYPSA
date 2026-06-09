<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_coleras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');

            $table->string('color')->nullable();
            $table->string('consistencia')->nullable();
            $table->string('vc01')->nullable();   // Vibrio Cholerae VC0-1
            $table->string('vc01_1')->nullable();  // Vibrio Cholerae VC01-1
            $table->string('vc0139')->nullable();  // Vibrio Cholerae VC0-139
            $table->text('observacion')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_coleras');
    }
};
