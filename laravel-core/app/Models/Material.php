<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'curso_id', 'titulo', 'tipo', 'url',
        'fecha_publicacion', 'descripcion', 'visible',
    ];

    protected $casts = ['fecha_publicacion' => 'date', 'visible' => 'boolean'];

    public function curso(): BelongsTo { return $this->belongsTo(Curso::class); }

    public function icono(): string
    {
        return match ($this->tipo) {
            'pdf'    => 'PDF',
            'video'  => 'Video',
            'enlace' => 'Enlace',
            'imagen' => 'Imagen',
            default  => 'Archivo',
        };
    }
}
