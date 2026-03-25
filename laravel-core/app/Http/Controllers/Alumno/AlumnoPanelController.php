<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\View\View;

class AlumnoPanelController extends Controller
{
    public function misCursos(): View
    {
        $user      = auth()->user();
        $alumno    = $user->alumno;
        $matricula = null;
        $cursos    = collect();

        if ($alumno) {
            $matricula = $alumno->matriculaActiva();

            if ($matricula && $matricula->plan) {
                $cursos = $matricula->plan
                    ->cursos()
                    ->where('activo', true)
                    ->with([
                        'clases'     => fn ($q) => $q->where('fecha', '>', now())->orderBy('fecha'),
                        'materiales' => fn ($q) => $q->where('visible', true)->orderByDesc('fecha_publicacion')->limit(5),
                    ])
                    ->orderBy('nivel')
                    ->orderBy('grado')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        return view('alumno.mis-cursos', compact('alumno', 'matricula', 'cursos'));
    }

    public function cursoDetalle(Curso $curso): View
    {
        $user      = auth()->user();
        $alumno    = $user->alumno;
        $matricula = $alumno?->matriculaActiva();

        // Verificar que el alumno tiene acceso a este curso a través de su plan
        $tieneAcceso = false;
        if ($matricula && $matricula->plan) {
            $tieneAcceso = $matricula->plan->cursos()
                ->where('cursos.id', $curso->id)
                ->where('cursos.activo', true)
                ->exists();
        }

        abort_unless($tieneAcceso, 403, 'No tienes acceso a este curso.');

        $curso->load([
            'clases'     => fn ($q) => $q->orderBy('fecha', 'desc'),
            'materiales' => fn ($q) => $q->where('visible', true)->orderByDesc('fecha_publicacion'),
        ]);

        return view('alumno.curso-detalle', compact('curso', 'matricula'));
    }
}
