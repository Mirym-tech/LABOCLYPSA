<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Orden extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'ordenes';

    protected $fillable = [
        'numero_orden', 'numero_entrada', 'numero_factura', 'tipo_paciente',
        'fecha_entrada', 'embarazada', 'estado',
        'paciente_id', 'laboratorio_id', 'creado_por', 'validado_por', 'validado_at',
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'embarazada' => 'boolean',
        'validado_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('ordenes');
    }

    public function paciente() { return $this->belongsTo(Paciente::class); }
    public function laboratorio() { return $this->belongsTo(Laboratorio::class); }
    public function creadoPor() { return $this->belongsTo(User::class, 'creado_por'); }
    public function validadoPor() { return $this->belongsTo(User::class, 'validado_por'); }
    public function analisis() { return $this->hasMany(OrdenAnalisis::class); }

    public static function generarNumero(): string
    {
        $ultimo = static::withTrashed()->orderByDesc('id')->value('numero_orden');
        $siguiente = $ultimo ? ((int) ltrim($ultimo, '0') + 1) : 1;
        return str_pad($siguiente, 8, '0', STR_PAD_LEFT);
    }
}
