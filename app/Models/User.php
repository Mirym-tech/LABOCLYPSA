<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, CausesActivity;

    protected $fillable = ['name', 'email', 'password', 'laboratorio_id', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function laboratorio() { return $this->belongsTo(Laboratorio::class); }
    public function pacientesCreados() { return $this->hasMany(Paciente::class, 'creado_por'); }
    public function ordenesCreadas() { return $this->hasMany(Orden::class, 'creado_por'); }

    public function getLaboratorioActivoId(): ?int
    {
        return session('laboratorio_activo_id') ?? $this->laboratorio_id;
    }
}
