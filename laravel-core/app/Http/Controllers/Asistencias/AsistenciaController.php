<?php

namespace App\Http\Controllers\Asistencias;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Clase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AsistenciaController extends Controller
{
    public function registrar(Clase $clase): View
    {
        $clase->load(['curso', 'asistencias']);

        $alumnosInscritos = $clase->curso
            ->alumnos()
            ->where('curso_alumno.activo', true)
            ->with('user')
            ->get();

        $asistenciasExistentes = $clase->asistencias->keyBy('alumno_id');

        return view('asistencias.registrar', compact('clase', 'alumnosInscritos', 'asistenciasExistentes'));
    }

    public function guardar(Request $request, Clase $clase): RedirectResponse
    {
        $request->validate([
            'asistencias'                 => 'required|array',
            'asistencias.*.estado'        => 'required|in:presente,ausente,tardanza,justificado',
            'asistencias.*.observacion'   => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $clase) {
            foreach ($request->asistencias as $alumnoId => $datos) {
                Asistencia::updateOrCreate(
                    ['clase_id' => $clase->id, 'alumno_id' => $alumnoId],
                    [
                        'estado'      => $datos['estado'],
                        'observacion' => $datos['observacion'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('clases.show', $clase)
            ->with('success', 'Asistencia registrada correctamente para ' . count($request->asistencias) . ' alumno(s).');
    }
}
