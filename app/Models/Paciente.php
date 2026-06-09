<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Paciente extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'codigo', 'nombre', 'direccion', 'telefono', 'cedula',
        'edad', 'sexo', 'nacionalidad', 'medico_tratante',
        'seguro_medico', 'cuenta', 'laboratorio_id', 'creado_por',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('pacientes');
    }

    public function laboratorio() { return $this->belongsTo(Laboratorio::class); }
    public function creadoPor() { return $this->belongsTo(User::class, 'creado_por'); }
    public function ordenes() { return $this->hasMany(Orden::class); }
}
