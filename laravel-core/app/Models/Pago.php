<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id', 'user_id', 'monto', 'metodo_pago', 'comprobante_url',
        'estado', 'fecha_pago', 'referencia', 'notas',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto'      => 'decimal:2',
    ];

    public function matricula(): BelongsTo { return $this->belongsTo(Matricula::class); }
    public function user(): BelongsTo      { return $this->belongsTo(User::class); }

    public function estaConfirmado(): bool   { return $this->estado === 'confirmado'; }
    public function estaVerificado(): bool   { return $this->estaConfirmado(); } // alias retrocompatible
    public function montoFormateado(): string { return 'S/. ' . number_format($this->monto, 2); }
}
