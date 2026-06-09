<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoUroanalisis extends Model
{
    use LogsActivity;

    protected $table = 'resultado_uroanalis';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id',
        'color', 'aspecto', 'densidad', 'ph', 'glucosa', 'proteina', 'acetona',
        'bilirrubina', 'urobilinogeno', 'sangre_oculta', 'hemoglobina', 'nitrito',
        'leucocitos', 'eritrocitos', 'celulas_epiteliales', 'celulas_renales',
        'bacterias', 'fibras_mucosas', 'cristales', 'cilindros', 'levaduras', 't_vaginalis',
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
