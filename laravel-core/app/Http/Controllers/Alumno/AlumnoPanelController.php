<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AlumnoPanelController extends Controller
{
    public function misCursos(): View
    {
        $user    = auth()->user();
        $alumno  = $user->alumno;
        $matricula = null;
        $cursos    = collect();

        if ($alumno) {
            $matricula = $alumno->matriculaActiva();

            if ($matricula && $matricula->plan) {
                $cursos = $matricula->plan
                    ->cursos()
                    ->where('activo', true)
                    ->orderBy('nivel')
                    ->orderBy('grado')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        return view('alumno.mis-cursos', compact('alumno', 'matricula', 'cursos'));
    }
}
