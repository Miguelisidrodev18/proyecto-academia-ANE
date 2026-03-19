<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'representante_id',
        'dni',
        'telefono',
        'origen_registro',
        'tipo',
        'estado',
        'acceso_activo',
        'racha_actual',
        'ultimo_acceso',
    ];

    protected $casts = [
        'estado'        => 'boolean',
        'acceso_activo' => 'boolean',
        'ultimo_acceso' => 'date',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function representante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'representante_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'curso_alumno')
                    ->withPivot(['fecha_inscripcion', 'activo'])
                    ->withTimestamps();
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function nombreCompleto(): string
    {
        return $this->user->name ?? '—';
    }

    public function inicial(): string
    {
        return strtoupper(substr($this->user->name ?? 'A', 0, 1));
    }

    public function esIntermedio(): bool
    {
        return $this->tipo === 'intermedio';
    }

    public function matriculaActiva(): ?Matricula
    {
        return $this->matriculas()->where('estado', 'activa')->latest()->first();
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeBuscar($query, string $texto)
    {
        return $query->where(function ($q) use ($texto) {
            $q->where('dni', 'like', "%{$texto}%")
              ->orWhereHas('user', fn ($u) =>
                  $u->where('name', 'like', "%{$texto}%")
                    ->orWhere('email', 'like', "%{$texto}%")
              );
        });
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
