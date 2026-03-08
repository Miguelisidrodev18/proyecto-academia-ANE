<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isDocente(): bool     { return $this->role === 'docente'; }
    public function isAlumno(): bool      { return $this->role === 'alumno'; }
    public function isRepresentante(): bool { return $this->role === 'representante'; }
    public function hasRole(string $role): bool { return $this->role === $role; }

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
            'password' => 'hashed',
        ];
    }
}
