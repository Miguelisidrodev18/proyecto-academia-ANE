<?php

namespace App\Http\Controllers\Reportes;

use App\Exports\ReporteAlumnoExport;
use App\Exports\ReporteClaseExport;
use App\Exports\ReporteCursoExport;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Clase;
use App\Models\Curso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function exportar(Request $request)
    {
        $request->validate([
            'tipo'    => ['required', 'in:alumno,clase,curso'],
            'formato' => ['required', 'in:excel,pdf'],
        ]);

        return match ("{$request->tipo}.{$request->formato}") {
            'alumno.excel' => $this->alumnoExcel($request),
            'alumno.pdf'   => $this->alumnoPdf($request),
            'clase.excel'  => $this->claseExcel($request),
            'clase.pdf'    => $this->clasePdf($request),
            'curso.excel'  => $this->cursoExcel($request),
            'curso.pdf'    => $this->cursoPdf($request),
        };
    }

    // ── API: cursos de un alumno ──────────────────────────────────────────────

    public function cursosDeAlumno(Alumno $alumno): JsonResponse
    {
        $cursoIds = $alumno->asistencias()
            ->join('clases', 'asistencias.clase_id', '=', 'clases.id')
            ->pluck('clases.curso_id')
            ->unique();

        $cursos = Curso::whereIn('id', $cursoIds)
            ->withCount('clases')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'nivel', 'tipo'])
            ->map(fn ($c) => [
                'id'          => $c->id,
                'nombre'      => $c->nombre,
                'nivel'       => ucfirst($c->nivel),
                'tipo'        => ucfirst($c->tipo ?? '—'),
                'clases_count' => $c->clases_count,
            ]);

        return response()->json($cursos);
    }

    // ── Por Alumno ────────────────────────────────────────────────────────────

    private function alumnoExcel(Request $request)
    {
        $request->validate(['alumno_id' => 'required', 'curso_id' => 'required']);

        $alumno = Alumno::with('user')->findOrFail($request->alumno_id);
        $curso  = Curso::findOrFail($request->curso_id);

        $slug  = str($alumno->nombreCompleto())->slug('-');
        $curso_slug = str($curso->nombre)->slug('-');

        return Excel::download(
            new ReporteAlumnoExport($alumno, $curso),
            "reporte-alumno-{$slug}-{$curso_slug}.xlsx"
        );
    }

    private function alumnoPdf(Request $request)
    {
        $request->validate(['alumno_id' => 'required', 'curso_id' => 'required']);

        $alumno  = Alumno::with('user')->findOrFail($request->alumno_id);
        $curso   = Curso::with(['clases' => fn ($q) => $q->orderBy('fecha')])->findOrFail($request->curso_id);
        $clases  = $curso->clases;
        $asistencias = $alumno->asistencias()->whereIn('clase_id', $clases->pluck('id'))->get()->keyBy('clase_id');

        $stats = $this->calcularStatsAlumno($clases, $asistencias);

        $pdf = Pdf::loadView('reportes.pdf-alumno', compact('alumno', 'curso', 'clases', 'asistencias', 'stats'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("reporte-alumno-{$alumno->dni}-{$curso->id}.pdf");
    }

    // ── Por Clase ─────────────────────────────────────────────────────────────

    private function claseExcel(Request $request)
    {
        $request->validate(['clase_id' => 'required']);

        $clase = Clase::with(['curso', 'asistencias.alumno.user'])->findOrFail($request->clase_id);
        $slug  = str($clase->titulo)->slug('-');

        return Excel::download(
            new ReporteClaseExport($clase),
            "reporte-clase-{$slug}.xlsx"
        );
    }

    private function clasePdf(Request $request)
    {
        $request->validate(['clase_id' => 'required']);

        $clase       = Clase::with(['curso', 'asistencias.alumno.user'])->findOrFail($request->clase_id);
        $asistencias = $clase->asistencias->sortBy('alumno.user.name');
        $stats       = $this->calcularStatsClase($asistencias);

        $pdf = Pdf::loadView('reportes.pdf-clase', compact('clase', 'asistencias', 'stats'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("reporte-clase-{$clase->id}.pdf");
    }

    // ── Resumen Curso ─────────────────────────────────────────────────────────

    private function cursoExcel(Request $request)
    {
        $request->validate(['curso_id' => 'required']);

        $curso = Curso::with(['clases.asistencias.alumno.user'])->findOrFail($request->curso_id);
        $slug  = str($curso->nombre)->slug('-');

        return Excel::download(
            new ReporteCursoExport($curso),
            "reporte-curso-{$slug}.xlsx"
        );
    }

    private function cursoPdf(Request $request)
    {
        $request->validate(['curso_id' => 'required']);

        $curso      = Curso::with(['clases' => fn ($q) => $q->orderBy('fecha'), 'clases.asistencias.alumno.user'])->findOrFail($request->curso_id);
        $clases     = $curso->clases;

        // Alumnos únicos inscritos en alguna clase
        $alumnosMap = collect();
        foreach ($clases as $clase) {
            foreach ($clase->asistencias as $a) {
                if ($a->alumno) $alumnosMap[$a->alumno_id] = $a->alumno;
            }
        }
        $alumnos = $alumnosMap->sortBy('user.name');

        $stats = $this->calcularStatsCurso($clases, $alumnos);

        $pdf = Pdf::loadView('reportes.pdf-curso', compact('curso', 'clases', 'alumnos', 'stats'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("reporte-curso-{$curso->id}.pdf");
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function calcularStatsAlumno($clases, $asistencias): array
    {
        $total       = $clases->count();
        $presente    = $asistencias->where('estado', 'presente')->count();
        $tardanza    = $asistencias->where('estado', 'tardanza')->count();
        $justificado = $asistencias->where('estado', 'justificado')->count();
        $ausente     = $asistencias->where('estado', 'ausente')->count();
        $pct         = $total > 0 ? round((($presente + $tardanza) / $total) * 100, 1) : 0;

        return compact('total', 'presente', 'tardanza', 'justificado', 'ausente', 'pct');
    }

    private function calcularStatsClase($asistencias): array
    {
        $total       = $asistencias->count();
        $presente    = $asistencias->where('estado', 'presente')->count();
        $tardanza    = $asistencias->where('estado', 'tardanza')->count();
        $justificado = $asistencias->where('estado', 'justificado')->count();
        $ausente     = $asistencias->where('estado', 'ausente')->count();
        $pct         = $total > 0 ? round((($presente + $tardanza) / $total) * 100, 1) : 0;

        return compact('total', 'presente', 'tardanza', 'justificado', 'ausente', 'pct');
    }

    private function calcularStatsCurso($clases, $alumnos): array
    {
        $totalClases  = $clases->count();
        $totalAlumnos = $alumnos->count();

        $promedioAsistencia = 0;
        if ($totalClases > 0) {
            $suma = $clases->sum(fn ($c) => $c->porcentajeAsistencia());
            $promedioAsistencia = round($suma / $totalClases, 1);
        }

        return compact('totalClases', 'totalAlumnos', 'promedioAsistencia');
    }
}
