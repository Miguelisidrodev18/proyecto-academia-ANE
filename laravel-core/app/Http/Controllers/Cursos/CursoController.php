<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function __construct(private CursoService $cursoService) {}

    public function index(): View
    {
        $cursos = Curso::withCount(['alumnos', 'clases'])
            ->orderBy('nivel')
            ->orderBy('grado')
            ->orderBy('nombre')
            ->get();

        $stats = [
            'total'      => $cursos->count(),
            'activos'    => $cursos->where('activo', true)->count(),
            'pollito'    => $cursos->whereIn('nivel', ['pollito', 'ambos'])->count(),
            'intermedio' => $cursos->whereIn('nivel', ['intermedio', 'ambos'])->count(),
        ];

        return view('cursos.index', compact('cursos', 'stats'));
    }

    public function create(): View
    {
        return view('cursos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'nivel'       => ['required', 'in:pollito,intermedio,ambos'],
            'grado'       => ['nullable', 'integer', 'min:1', 'max:5'],
            'tipo'        => ['required', 'in:reforzamiento,preuniversitario'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        $curso = $this->cursoService->crear($data);

        return redirect()->route('cursos.show', $curso)
            ->with('success', 'Curso "' . $curso->nombre . '" creado correctamente.');
    }

    public function show(Curso $curso): View
    {
        $curso->load(['clases' => fn ($q) => $q->orderBy('fecha', 'desc'), 'planes']);

        return view('cursos.show', compact('curso'));
    }

    public function edit(Curso $curso): View
    {
        return view('cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso): RedirectResponse
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'nivel'       => ['required', 'in:pollito,intermedio,ambos'],
            'grado'       => ['nullable', 'integer', 'min:1', 'max:5'],
            'tipo'        => ['required', 'in:reforzamiento,preuniversitario'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        $this->cursoService->actualizar($curso, $data);

        return redirect()->route('cursos.show', $curso)
            ->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Curso $curso): RedirectResponse
    {
        if ($curso->alumnos()->exists()) {
            return back()->with('error', 'No se puede eliminar el curso porque tiene alumnos inscritos.');
        }

        $nombre = $curso->nombre;
        $this->cursoService->eliminar($curso);

        return redirect()->route('cursos.index')
            ->with('success', 'Curso "' . $nombre . '" eliminado correctamente.');
    }

    public function toggle(Curso $curso): RedirectResponse
    {
        $activo = $this->cursoService->toggleActivo($curso);
        $estado = $activo ? 'activado' : 'desactivado';

        return back()->with('success', 'Curso "' . $curso->nombre . '" ' . $estado . ' correctamente.');
    }
}
