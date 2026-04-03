<?php

namespace App\Exports;

use App\Models\Curso;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteCursoExport implements WithMultipleSheets
{
    private Curso $curso;

    public function __construct(Curso $curso)
    {
        $this->curso = $curso->load(['clases' => fn ($q) => $q->orderBy('fecha'), 'clases.asistencias.alumno.user']);
    }

    public function sheets(): array
    {
        return [
            new ReporteCursoResumenSheet($this->curso),
            new ReporteCursoDetalleSheet($this->curso),
        ];
    }
}
