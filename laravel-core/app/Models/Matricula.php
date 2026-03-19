<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumno_id', 'plan_id', 'precio_pagado', 'tipo_pago', 'fecha_inicio', 'fecha_fin',
        'estado', 'dias_cortesia', 'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function alumno(): BelongsTo { return $this->belongsTo(Alumno::class); }
    public function plan(): BelongsTo   { return $this->belongsTo(Plan::class); }
    public function pagos(): HasMany    { return $this->hasMany(Pago::class); }
    public function cuotas(): HasMany   { return $this->hasMany(Cuota::class)->orderBy('numero'); }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function tieneAcceso(): bool
    {
        if ($this->estado !== 'activa') {
            return false;
        }

        if ($this->plan?->acceso_ilimitado) {
            return true;
        }

        if ($this->fecha_fin && $this->fecha_fin->isPast()) {
            return false;
        }

        // Si tiene cuotas vencidas sin pagar → no tiene acceso
        if ($this->cuotas()->where('estado', 'vencida')->exists()) {
            return false;
        }

        return true;
    }
    public function estaActiva(): bool   { return $this->estado === 'activa'; }

    public function diasRestantes(): int
    {
        return max(0, now()->diffInDays($this->fecha_fin, false));
    }

    public function totalPagado(): float
    {
        return (float) $this->pagos()->where('estado', 'confirmado')->sum('monto');
    }

    public function saldoPendiente(): float
    {
        return $this->precio_pagado - $this->totalPagado();
    }
}
