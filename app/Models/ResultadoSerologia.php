<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoSerologia extends Model
{
    use LogsActivity;

    protected $table = 'resultado_serologias';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id', 'reportar',
        'salmonella_o_a', 'salmonella_o_b', 'salmonella_o_c', 'salmonella_o_d',
        'salmonella_h_a', 'salmonella_h_b', 'salmonella_h_c', 'salmonella_h_d',
        'proteus_ox2', 'proteus_ox19', 'proteus_oxk',
        'brucella_abortus', 'typhoide_o_somatica', 'observacion',
        'validado', 'validado_por', 'validado_at',
    ];

    protected $casts = ['validado' => 'boolean', 'reportar' => 'boolean', 'validado_at' => 'datetime'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('resultados');
    }

    public function ordenAnalisis() { return $this->belongsTo(OrdenAnalisis::class); }
    public function bioanalista() { return $this->belongsTo(User::class, 'bioanalista_id'); }
    public function validadoPor() { return $this->belongsTo(User::class, 'validado_por'); }
}
