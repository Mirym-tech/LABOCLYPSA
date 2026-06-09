<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoVarios extends Model
{
    use LogsActivity;

    protected $table = 'resultado_varios';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id',
        'grupo', 'sub_grupo', 'resultado', 'metodo', 'medidas', 'muestra', 'valor_ref',
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
