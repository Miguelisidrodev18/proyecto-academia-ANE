<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'nivel', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function clases(): HasMany     { return $this->hasMany(Clase::class); }
    public function materiales(): HasMany { return $this->hasMany(Material::class); }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'curso_alumno')
                    ->withPivot(['fecha_inscripcion', 'activo'])
                    ->withTimestamps();
    }

    public function proximaClase(): ?Clase
    {
        return $this->clases()->where('fecha', '>', now())->orderBy('fecha')->first();
    }
}
