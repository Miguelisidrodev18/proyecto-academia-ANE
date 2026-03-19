<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'precio',
        'duracion_meses',
        'acceso_ilimitado',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'acceso_ilimitado' => 'boolean',
        'activo'           => 'boolean',
        'precio'           => 'decimal:2',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'plan_curso');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function precioFormateado(): string
    {
        return 'S/. ' . number_format($this->precio, 2);
    }
}
