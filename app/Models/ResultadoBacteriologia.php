<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoBacteriologia extends Model
{
    use LogsActivity;

    protected $table = 'resultado_bacteriologias';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id', 'estudio', 'muestra_de', 'organismo', 'aislados',
        'penicilina', 'piperacilina', 'carbenicilina', 'ampicilina', 'amoxicilina',
        'cefalexina', 'cefotaxina', 'norfloxacin', 'karamicina', 'gentamicina',
        'tabramicina', 'amikacina', 'ceftriazona', 'cefazolin',
        'tetraciclina', 'minociclina', 'eritrociclina', 'lincomicina', 'fosfocil',
        'cefepime', 'ac_nalidixico', 'amox_ac_clav', 'levofloxacin', 'furadantoina',
        'ciproflaxacina', 'clindamicina', 'sulfatrym', 'vancomicina', 'imipenen', 'cefunoxima',
        'epitelios', 'leucocitos_micro', 'hematies', 'tincion_gram', 'tincion_ziehl',
        'bacterias', 'levaduras', 't_vaginalis', 'observacion',
        'validado', 'validado_por', 'validado_at',
    ];

    protected $casts = ['validado' => 'boolean', 'validado_at' => 'datetime'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('resultados');
    }

    public function ordenAnalisis() { return $this->belongsTo(OrdenAnalisis::class); }
    public function bioanalista() { return $this->belongsTo(User::class, 'bioanalista_id'); }
    public function validadoPor() { return $this->belongsTo(User::class, 'validado_por'); }
}
