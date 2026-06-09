<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResultadoHematologia extends Model
{
    use LogsActivity;

    protected $table = 'resultado_hematologias';

    protected $fillable = [
        'orden_analisis_id', 'bioanalista_id',
        'wbc', 'lymph_abs', 'mid_abs', 'gran_abs', 'lymph_pct', 'mid_pct', 'gran_pct',
        'rbc', 'hgb', 'hct', 'mcv', 'mch', 'mchc', 'rdw_cv', 'rdw_sd',
        'plt', 'mpv', 'pdw', 'pct', 'plcr',
        'vitamina_b12', 'acido_folico', 'hierro', 'observacion_general',
        'hemoglobina_gdl', 'hemoglobina_pct', 'hematocrito_pct', 'eritrocitos', 'leucocitos',
        'vcm', 'hcm', 'chcm',
        'mieloblastos', 'promielocitos', 'mielocitos', 'metamielocitos',
        'bandas', 'segmentos', 'linfocitos', 'monocitos', 'eosinofilos', 'basofilos',
        'hipocromia', 'poiquilocitosis', 'anisocitosis', 'cls_en_diana',
        'macrocitosis', 'cls_crenadas', 'microcitosis', 'macroplaquet',
        'eritrosedimentacion', 'conteo_eosinofilos', 'conteo_plaquetas',
        'conteo_reticulocitos', 'reticulocitos_corregidos',
        'inv_falcemia', 'inv_celulas_le', 'inv_hematozoarios', 'observacion_hemograma',
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
