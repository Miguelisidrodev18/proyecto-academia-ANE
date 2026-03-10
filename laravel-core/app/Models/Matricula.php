<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // ── Helpers ───────────────────────────────────────────────────────────────

    // Método helper para saber si tiene acceso hoy
    public function tieneAcceso(): bool
    {
        if ($this->tipo_pago === 'completo') {
            return $this->estado === 'activa' && $this->fecha_fin->isFuture();
        }
        
        if ($this->tipo_pago === 'mensual') {
            // Lógica: verificar si pagó el mes actual
            return $this->pagos()
                ->where('estado', 'pagado')
                ->whereMonth('fecha_pago', now()->month)
                ->exists();
        }
        
        // tipo_pago === 'cuotas'
        // Lógica más compleja, depende de cuántas cuotas pagó
        return $this->pagos()
            ->where('estado', 'pagado')
            ->sum('monto') >= $this->precio_pagado / 2; // ej: mínimo 50%
    }
    public function estaActiva(): bool   { return $this->estado === 'activa'; }

    public function diasRestantes(): int
    {
        return max(0, now()->diffInDays($this->fecha_fin, false));
    }

    public function totalPagado(): float
    {
        return (float) $this->pagos()->where('estado', 'verificado')->sum('monto');
    }

    public function saldoPendiente(): float
    {
        return $this->precio_pagado - $this->totalPagado();
    }
}
