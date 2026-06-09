<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoCoprologia extends Model
{
    use LogsActivity;

    protected $table = 'resultado_coprologicos';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id',
        'tipo_estudio', 'color', 'consistencia', 'sin_parasitos',
        'se_observan', 'sangre_oculta', 'invest_amebas', 'observacion',
        'validado', 'validado_por', 'validado_at',
    ];

    protected $casts = ['validado' => 'boolean', 'sin_parasitos' => 'boolean', 'validado_at' => 'datetime'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('resultados');
    }

    public function ordenAnalisis() { return $this->belongsTo(OrdenAnalisis::class); }
    public function bioanalista() { return $this->belongsTo(User::class, 'bioanalista_id'); }
    public function validadoPor() { return $this->belongsTo(User::class, 'validado_por'); }
}
