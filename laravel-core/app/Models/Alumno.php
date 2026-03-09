<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'tipo',
        'fecha_nacimiento',
        'direccion',
        'documentos',
        'estado',
    ];

    protected $casts = [
        'documentos'       => 'array',
        'estado'           => 'boolean',
        'fecha_nacimiento' => 'date',
    ];

    // --- Helpers ---

    public function nombreCompleto(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function esPremium(): bool
    {
        return $this->tipo === 'premium';
    }

    public function inicial(): string
    {
        return strtoupper(substr($this->nombres, 0, 1));
    }

    // --- Scopes ---

    public function scopeBuscar($query, string $texto)
    {
        return $query->where(function ($q) use ($texto) {
            $q->where('nombres', 'like', "%{$texto}%")
              ->orWhere('apellidos', 'like', "%{$texto}%")
              ->orWhere('dni', 'like', "%{$texto}%")
              ->orWhere('email', 'like', "%{$texto}%");
        });
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
