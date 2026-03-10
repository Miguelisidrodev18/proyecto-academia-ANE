<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class);
    }

    // ── Helpers de rol ────────────────────────────────────────────────────────

    public function isAdmin(): bool         { return $this->role === 'admin'; }
    public function isDocente(): bool       { return $this->role === 'docente'; }
    public function isAlumno(): bool        { return $this->role === 'alumno'; }
    public function isRepresentante(): bool { return $this->role === 'representante'; }
    public function hasRole(string $role): bool { return $this->role === $role; }
}
