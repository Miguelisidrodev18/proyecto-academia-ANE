<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['alumno_id', 'total', 'estado', 'notas'];

    protected $casts = ['total' => 'decimal:2'];

    public function alumno(): BelongsTo  { return $this->belongsTo(Alumno::class); }
    public function detalles(): HasMany  { return $this->hasMany(DetallePedido::class); }

    public function totalFormateado(): string { return 'S/. ' . number_format($this->total, 2); }
}
