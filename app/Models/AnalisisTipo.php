<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisisTipo extends Model
{
    protected $table = 'analisis_tipos';
    protected $fillable = ['codigo', 'nombre', 'categoria', 'precio', 'activo'];

    public function ordenAnalisis() { return $this->hasMany(OrdenAnalisis::class); }
}
