<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id',
        'numero',
        'monto',
        'fecha_vencimiento',
        'estado',
        'fecha_pago',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_pago'        => 'date',
        'monto'             => 'decimal:2',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function matricula(): BelongsTo { return $this->belongsTo(Matricula::class); }
    public function pago(): HasOne         { return $this->hasOne(Pago::class); }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function esPendiente(): bool { return $this->estado === 'pendiente'; }
    public function esPagada(): bool    { return $this->estado === 'pagada'; }
    public function esVencida(): bool   { return $this->estado === 'vencida'; }

    public function estaVencidaSinPagar(): bool
    {
        return $this->estado === 'pendiente' && $this->fecha_vencimiento->isPast();
    }

    public function montoFormateado(): string
    {
        return 'S/. ' . number_format($this->monto, 2);
    }

    public function estadoBadgeClass(): string
    {
        return match($this->estado) {
            'pagada'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'vencida'  => 'bg-red-50 text-red-700 border-red-200',
            default    => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }

    public function estadoLabel(): string
    {
        return match($this->estado) {
            'pagada'   => 'Pagada',
            'vencida'  => 'Vencida',
            default    => 'Pendiente',
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', 'vencida');
    }
}
