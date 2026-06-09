<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoDigestion extends Model
{
    use LogsActivity;

    protected $table = 'resultado_digestiones';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id',
        'color', 'olor', 'consistencia', 'alimentos_no_digeridos', 'mucus',
        'reaccion_ph', 'sangre_oculta', 'grasas', 'sustancia_reductora', 'tripsina',
        'leucocitos', 'eritrocitos', 'celulas_epiteliales', 'fibras_mucosas', 'cristales',
        'bacterias', 'huevos', 'parasitos', 'quistes', 'granulos', 'larvas',
        'materiales_extranos', 'observacion',
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
