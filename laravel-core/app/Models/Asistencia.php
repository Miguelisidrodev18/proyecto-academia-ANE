<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = ['clase_id', 'alumno_id', 'estado', 'observacion', 'hora_ingreso'];

    protected $casts = ['hora_ingreso' => 'datetime'];

    public function clase(): BelongsTo  { return $this->belongsTo(Clase::class); }
    public function alumno(): BelongsTo { return $this->belongsTo(Alumno::class); }
    public function esPresente(): bool  { return $this->estado === 'presente'; }
}
