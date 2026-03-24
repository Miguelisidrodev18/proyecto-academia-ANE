<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClaseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Clase::with('curso')->orderByDesc('fecha');

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('estado')) {
            match ($request->estado) {
                'proxima' => $query->where('fecha', '>', now()),
                'pasada'  => $query->where('fecha', '<=', now()),
                default   => null,
            };
        }

        $clases = $query->paginate(15)->withQueryString();
        $cursos = Curso::where('activo', true)->orderBy('nombre')->get();

        $stats = [
            'total'         => Clase::count(),
            'proximas'      => Clase::where('fecha', '>', now())->count(),
            'pasadas'       => Clase::where('fecha', '<=', now())->count(),
            'con_grabacion' => Clase::where('grabada', true)->whereNotNull('grabacion_url')->count(),
        ];

        return view('clases.index', compact('clases', 'cursos', 'stats'));
    }

    public function create(Request $request): View
    {
        $cursos            = Curso::where('activo', true)->orderBy('nombre')->get();
        $cursoSeleccionado = $request->filled('curso_id') ? Curso::find($request->curso_id) : null;

        return view('clases.create', compact('cursos', 'cursoSeleccionado'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'curso_id'      => 'required|exists:cursos,id',
            'titulo'        => 'required|string|max:200',
            'fecha'         => 'required|date',
            'zoom_link'     => 'nullable|url|max:500',
            'descripcion'   => 'nullable|string|max:1000',
            'grabacion_url' => 'nullable|url|max:500',
        ]);

        $data['grabada'] = ! empty($data['grabacion_url']);

        $clase = Clase::create($data);

        return redirect()->route('clases.show', $clase)
            ->with('success', 'Clase "' . $clase->titulo . '" creada correctamente.');
    }

    public function show(Clase $clase): View
    {
        $clase->load(['curso', 'asistencias.alumno.user']);

        $alumnosInscritos = $clase->curso
            ->alumnos()
            ->where('curso_alumno.activo', true)
            ->with('user')
            ->get();

        return view('clases.show', compact('clase', 'alumnosInscritos'));
    }

    public function edit(Clase $clase): View
    {
        $cursos = Curso::where('activo', true)->orderBy('nombre')->get();

        return view('clases.edit', compact('clase', 'cursos'));
    }

    public function update(Request $request, Clase $clase): RedirectResponse
    {
        $data = $request->validate([
            'curso_id'      => 'required|exists:cursos,id',
            'titulo'        => 'required|string|max:200',
            'fecha'         => 'required|date',
            'zoom_link'     => 'nullable|url|max:500',
            'descripcion'   => 'nullable|string|max:1000',
            'grabacion_url' => 'nullable|url|max:500',
        ]);

        $data['grabada'] = ! empty($data['grabacion_url']);

        $clase->update($data);

        return redirect()->route('clases.show', $clase)
            ->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase): RedirectResponse
    {
        if ($clase->asistencias()->exists()) {
            return back()->with('error', 'No se puede eliminar esta clase porque tiene registros de asistencia guardados.');
        }

        $titulo = $clase->titulo;
        $clase->delete();

        return redirect()->route('clases.index')
            ->with('success', 'Clase "' . $titulo . '" eliminada.');
    }
}
