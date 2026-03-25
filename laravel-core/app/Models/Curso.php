<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'nivel', 'grado', 'tipo',
        'imagen_url', 'zoom_link', 'dias_semana', 'hora_inicio', 'activo',
    ];

    protected $casts = [
        'activo'      => 'boolean',
        'grado'       => 'integer',
        'dias_semana' => 'array',
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

    /**
     * Alumnos que acceden a este curso a través de su plan activo.
     * Usar en lugar de alumnos() ya que curso_alumno no se puebla directamente.
     */
    public function alumnosViaPlanes(): \Illuminate\Database\Eloquent\Builder
    {
        $cursoId = $this->id;

        return Alumno::whereHas('matriculas', function ($q) use ($cursoId) {
            $q->where('estado', 'activa')
              ->whereHas('plan', function ($p) use ($cursoId) {
                  $p->whereHas('cursos', function ($c) use ($cursoId) {
                      $c->where('cursos.id', $cursoId);
                  });
              });
        });
    }

    /** True si hoy es uno de los días configurados del curso. */
    public function estaActivoHoy(): bool
    {
        if (empty($this->dias_semana)) return false;

        $mapa = [0 => 'domingo', 1 => 'lunes', 2 => 'martes', 3 => 'miercoles',
                 4 => 'jueves',  5 => 'viernes', 6 => 'sabado'];

        $hoy = $mapa[now()->dayOfWeek];
        return in_array($hoy, $this->dias_semana);
    }

    /** Devuelve los días como etiquetas cortas: ["Lu","Mi","Vi"] */
    public function diasLabels(): array
    {
        if (empty($this->dias_semana)) return [];

        $etiquetas = [
            'lunes'     => 'Lu', 'martes'   => 'Ma', 'miercoles' => 'Mi',
            'jueves'    => 'Ju', 'viernes'  => 'Vi', 'sabado'    => 'Sá',
            'domingo'   => 'Do',
        ];

        return array_values(array_filter(
            array_map(fn ($d) => $etiquetas[$d] ?? null, $this->dias_semana)
        ));
    }

    /** Orden canónico de días de la semana. */
    public static function ordenDias(): array
    {
        return ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
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
