<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellidos', 'email', 'telefono',
        'nivel', 'plan_id', 'mensaje',
        'estado', 'notas_admin', 'origen', 'contactado_en',
    ];

    protected $casts = [
        'contactado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function nombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    public function inicial(): string
    {
        return strtoupper(substr($this->nombre, 0, 1));
    }

    public function nivelLabel(): string
    {
        return match($this->nivel) {
            'pollito'   => '🐣 Pollito',
            'intermedio'=> '⚡ Intermedio',
            default     => '❓ No sabe',
        };
    }

    public function estadoLabel(): string
    {
        return match($this->estado) {
            'nuevo'       => 'Nuevo',
            'contactado'  => 'Contactado',
            'matriculado' => 'Matriculado',
            'descartado'  => 'Descartado',
            default       => 'Nuevo',
        };
    }

    public function estadoColor(): string
    {
        return match($this->estado) {
            'nuevo'       => 'bg-blue-50 text-blue-700 border-blue-200',
            'contactado'  => 'bg-amber-50 text-amber-700 border-amber-200',
            'matriculado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'descartado'  => 'bg-gray-100 text-gray-500 border-gray-200',
            default       => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }

    public function estadoDot(): string
    {
        return match($this->estado) {
            'nuevo'       => 'bg-blue-500',
            'contactado'  => 'bg-amber-500',
            'matriculado' => 'bg-emerald-500',
            'descartado'  => 'bg-gray-400',
            default       => 'bg-blue-500',
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeBuscar($query, string $texto)
    {
        return $query->where(function ($q) use ($texto) {
            $q->where('nombre', 'like', "%{$texto}%")
              ->orWhere('apellidos', 'like', "%{$texto}%")
              ->orWhere('email', 'like', "%{$texto}%")
              ->orWhere('telefono', 'like', "%{$texto}%");
        });
    }
}
