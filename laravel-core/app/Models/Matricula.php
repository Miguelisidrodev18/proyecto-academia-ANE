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
        'alumno_id', 'plan_id', 'fecha_inicio', 'fecha_fin',
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

    public function estaActiva(): bool   { return $this->estado === 'activa'; }

    public function diasRestantes(): int
    {
        return max(0, now()->diffInDays($this->fecha_fin, false));
    }

    public function totalPagado(): float
    {
        return (float) $this->pagos()->where('estado', 'verificado')->sum('monto');
    }
}
