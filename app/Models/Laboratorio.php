<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'telefono', 'ciudad', 'activo'];

    public function usuarios() { return $this->hasMany(User::class); }
    public function pacientes() { return $this->hasMany(Paciente::class); }
    public function ordenes() { return $this->hasMany(Orden::class); }
}
