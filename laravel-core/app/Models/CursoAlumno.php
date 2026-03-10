<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CursoAlumno extends Pivot
{
    protected $table = 'curso_alumno';

    protected $fillable = ['alumno_id', 'curso_id', 'fecha_inscripcion', 'activo'];

    protected $casts = ['fecha_inscripcion' => 'date', 'activo' => 'boolean'];
}
