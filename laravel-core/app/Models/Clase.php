<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clase extends Model
{
    use HasFactory;

    protected $fillable = [
        'curso_id', 'titulo', 'fecha', 'zoom_link',
        'descripcion', 'grabada', 'grabacion_url',
    ];

    protected $casts = ['fecha' => 'datetime', 'grabada' => 'boolean'];

    public function curso(): BelongsTo      { return $this->belongsTo(Curso::class); }
    public function asistencias(): HasMany  { return $this->hasMany(Asistencia::class); }

    public function totalPresentes(): int { return $this->asistencias()->where('estado', 'presente')->count(); }

    public function porcentajeAsistencia(): float
    {
        $total = $this->asistencias()->count();
        return $total === 0 ? 0.0 : round(($this->totalPresentes() / $total) * 100, 1);
    }
}
