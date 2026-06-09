<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenAnalisis extends Model
{
    protected $table = 'orden_analisis';
    protected $fillable = ['orden_id', 'analisis_tipo_id', 'estado'];

    public function orden() { return $this->belongsTo(Orden::class); }
    public function tipo() { return $this->belongsTo(AnalisisTipo::class, 'analisis_tipo_id'); }
    public function resultadoHematologia() { return $this->hasOne(ResultadoHematologia::class); }
    public function resultadoBacteriologia() { return $this->hasOne(ResultadoBacteriologia::class); }
    public function resultadoSerologia() { return $this->hasOne(ResultadoSerologia::class); }
    public function resultadoColera() { return $this->hasOne(ResultadoColera::class); }
    public function resultadoUroanalisis() { return $this->hasOne(ResultadoUroanalisis::class); }
    public function resultadoCoprologia() { return $this->hasOne(ResultadoCoprologia::class); }
    public function resultadoDigestion() { return $this->hasOne(ResultadoDigestion::class); }
    public function resultadoVarios() { return $this->hasMany(ResultadoVarios::class); }
}
