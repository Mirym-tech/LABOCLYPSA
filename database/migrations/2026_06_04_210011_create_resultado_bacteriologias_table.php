<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_bacteriologias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_analisis_id')->constrained('orden_analisis')->cascadeOnDelete();
            $table->foreignId('bioanalista_id')->nullable()->constrained('users');

            // Pestaña General
            $table->string('estudio')->nullable();
            $table->string('muestra_de')->nullable();
            $table->string('organismo')->nullable();
            $table->text('aislados')->nullable();

            // Antibiograma (S=Sensible, R=Resistente, I=Intermedio)
            $table->string('penicilina')->nullable();
            $table->string('piperacilina')->nullable();
            $table->string('carbenicilina')->nullable();
            $table->string('ampicilina')->nullable();
            $table->string('amoxicilina')->nullable();
            $table->string('cefalexina')->nullable();
            $table->string('cefotaxina')->nullable();
            $table->string('norfloxacin')->nullable();
            $table->string('karamicina')->nullable();
            $table->string('gentamicina')->nullable();
            $table->string('tabramicina')->nullable();
            $table->string('amikacina')->nullable();
            $table->string('ceftriazona')->nullable();
            $table->string('cefazolin')->nullable();

            // Pestaña Cont.
            $table->string('tetraciclina')->nullable();
            $table->string('minociclina')->nullable();
            $table->string('eritrociclina')->nullable();
            $table->string('lincomicina')->nullable();
            $table->string('fosfocil')->nullable();
            $table->string('cefepime')->nullable();
            $table->string('ac_nalidixico')->nullable();
            $table->string('amox_ac_clav')->nullable();
            $table->string('levofloxacin')->nullable();
            $table->string('furadantoina')->nullable();
            $table->string('ciproflaxacina')->nullable();
            $table->string('clindamicina')->nullable();
            $table->string('sulfatrym')->nullable();
            $table->string('vancomicina')->nullable();
            $table->string('imipenen')->nullable();
            $table->string('cefunoxima')->nullable();

            // Pestaña Cont. 3 — Examen Microscópico
            $table->string('epitelios')->nullable();
            $table->string('leucocitos_micro')->nullable();
            $table->string('hematies')->nullable();
            $table->string('tincion_gram')->nullable();
            $table->string('tincion_ziehl')->nullable();
            $table->string('bacterias')->nullable();
            $table->string('levaduras')->nullable();
            $table->string('t_vaginalis')->nullable();
            $table->text('observacion')->nullable();

            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_bacteriologias');
    }
};
