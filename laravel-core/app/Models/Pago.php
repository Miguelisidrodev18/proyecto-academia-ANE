<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id', 'monto', 'metodo_pago', 'comprobante_url',
        'estado', 'fecha_pago', 'referencia', 'notas',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto'      => 'decimal:2',
    ];

    public function matricula(): BelongsTo { return $this->belongsTo(Matricula::class); }

    public function estaVerificado(): bool   { return $this->estado === 'verificado'; }
    public function montoFormateado(): string { return 'S/. ' . number_format($this->monto, 2); }
}
