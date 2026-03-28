<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
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
                        'clases'     => fn ($q) => $q->whereDate('fecha', '>=', today())->orderBy('fecha'),
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

    public function zoom(Curso $curso): RedirectResponse
    {
        $user      = auth()->user();
        $alumno    = $user->alumno;
        $matricula = $alumno?->matriculaActiva();

        $tieneAcceso = $matricula && $matricula->tieneAcceso() && $matricula->plan->cursos()
            ->where('cursos.id', $curso->id)
            ->where('cursos.activo', true)
            ->exists();

        if (!$alumno || !$tieneAcceso || !$curso->zoom_link) {
            return redirect()->route('alumno.mis-cursos');
        }

        // Solo registrar si el admin/docente creó la clase de hoy
        $claseHoy = $curso->clases()->whereDate('fecha', today())->first();

        if ($claseHoy) {
            $asistencia = Asistencia::where('clase_id', $claseHoy->id)
                ->where('alumno_id', $alumno->id)
                ->first();

            if (!$asistencia) {
                Asistencia::create([
                    'clase_id'     => $claseHoy->id,
                    'alumno_id'    => $alumno->id,
                    'estado'       => 'presente',
                    'hora_ingreso' => now(),
                ]);
            } elseif (!$asistencia->hora_ingreso) {
                $asistencia->update(['hora_ingreso' => now(), 'estado' => 'presente']);
            }
            // Si ya tiene hora_ingreso: no hacer nada (ya se registró)
        }

        return redirect()->away($curso->zoom_link);
    }

    public function asistencias(): View
    {
        $user   = auth()->user();
        $alumno = $user->alumno;

        $asistencias = collect();
        $stats = ['total' => 0, 'presentes' => 0, 'tardanzas' => 0, 'justificados' => 0, 'ausentes' => 0];

        if ($alumno) {
            $asistencias = $alumno->asistencias()
                ->with(['clase.curso'])
                ->orderByDesc('created_at')
                ->get();

            $stats = [
                'total'        => $asistencias->count(),
                'presentes'    => $asistencias->where('estado', 'presente')->count(),
                'tardanzas'    => $asistencias->where('estado', 'tardanza')->count(),
                'justificados' => $asistencias->where('estado', 'justificado')->count(),
                'ausentes'     => $asistencias->where('estado', 'ausente')->count(),
            ];
        }

        return view('alumno.asistencias', compact('alumno', 'asistencias', 'stats'));
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
