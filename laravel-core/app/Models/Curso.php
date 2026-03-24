<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'nivel', 'grado', 'tipo', 'imagen_url', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
        'grado'  => 'integer',
    ];

    public function clases(): HasMany     { return $this->hasMany(Clase::class); }
    public function materiales(): HasMany { return $this->hasMany(Material::class); }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'curso_alumno')
                    ->withPivot(['fecha_inscripcion', 'activo'])
                    ->withTimestamps();
    }

    public function planes(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_curso');
    }

    public function proximaClase(): ?Clase
    {
        return $this->clases()->where('fecha', '>', now())->orderBy('fecha')->first();
    }

    public function nivelLabel(): string
    {
        return match($this->nivel) {
            'pollito'     => 'Pollito (Escolar)',
            'intermedio'  => 'Intermedio (Pre-Universitario)',
            'ambos'       => 'Todos los niveles',
            default       => ucfirst($this->nivel),
        };
    }

    public function tipoLabel(): string
    {
        return match($this->tipo) {
            'reforzamiento'    => 'Reforzamiento',
            'preuniversitario' => 'Pre-Universitario',
            default            => ucfirst($this->tipo),
        };
    }

    public function gradoLabel(): string
    {
        if (!$this->grado) {
            return '—';
        }
        return $this->grado . '.° grado';
    }
}
