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
        'tipo_plan',
        'precio',
        'duracion_meses',
        'acceso_ilimitado',
        'descripcion',
        'activo',
        'mostrar_en_landing',
    ];

    protected $casts = [
        'acceso_ilimitado'    => 'boolean',
        'activo'              => 'boolean',
        'mostrar_en_landing'  => 'boolean',
        'precio'              => 'decimal:2',
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

    public function esVip(): bool
    {
        return $this->tipo_plan === 'vip';
    }

    public function esPremium(): bool
    {
        return $this->tipo_plan === 'premium';
    }

    public function tipoIcono(): string
    {
        return $this->esVip() ? '💎' : '⭐';
    }

    public function tipoLabel(): string
    {
        return $this->esVip() ? 'VIP' : 'Premium';
    }

    public function precioFormateado(): string
    {
        return 'S/. ' . number_format($this->precio, 2);
    }
}
