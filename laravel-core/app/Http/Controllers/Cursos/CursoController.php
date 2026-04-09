<?php

namespace App\Http\Controllers\Cursos;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Plan;
use App\Services\CursoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function __construct(private CursoService $cursoService) {}

    public function index(): View
    {
        $cursos = Curso::withCount(['clases', 'materiales'])
            ->with(['planes:id,nombre,tipo_plan'])
            ->addSelect(DB::raw('(
                SELECT COUNT(DISTINCT alumnos.id)
                FROM alumnos
                INNER JOIN matriculas ON matriculas.alumno_id = alumnos.id AND matriculas.estado = "activa"
                INNER JOIN plan_curso ON plan_curso.plan_id = matriculas.plan_id AND plan_curso.curso_id = cursos.id
                WHERE alumnos.deleted_at IS NULL
            ) as alumnos_count'))
            ->orderBy('nivel')
            ->orderBy('grado')
            ->orderBy('nombre')
            ->get();

        // Agrupar cursos por plan para la vista en secciones
        $planes = Plan::orderBy('precio')->get();

        $cursosPorPlan = $planes->mapWithKeys(fn ($plan) => [
            $plan->id => $cursos->filter(fn ($c) => $c->planes->contains('id', $plan->id))->values(),
        ])->filter(fn ($lista) => $lista->isNotEmpty());

        $sinPlan = $cursos->filter(fn ($c) => $c->planes->isEmpty())->values();

        $stats = [
            'total'      => $cursos->count(),
            'activos'    => $cursos->where('activo', true)->count(),
            'pollito'    => $cursos->whereIn('nivel', ['pollito', 'ambos'])->count(),
            'intermedio' => $cursos->whereIn('nivel', ['intermedio', 'ambos'])->count(),
        ];

        return view('cursos.index', compact('cursos', 'cursosPorPlan', 'planes', 'sinPlan', 'stats'));
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
            'zoom_link'    => ['nullable', 'url', 'max:500'],
            'dias_semana'  => ['nullable', 'array'],
            'dias_semana.*'=> ['in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'hora_inicio'  => ['nullable', 'date_format:H:i'],
            'imagen'       => ['nullable', 'image', 'max:2048'],
            'activo'       => ['nullable', 'boolean'],
        ]);

        $curso = $this->cursoService->crear($data, $request->file('imagen'));

        return redirect()->route('cursos.show', $curso)
            ->with('success', 'Curso "' . $curso->nombre . '" creado correctamente.');
    }

    public function show(Curso $curso): View
    {
        $curso->load([
            'clases'             => fn ($q) => $q->orderBy('fecha', 'desc'),
            'clases.materiales',
            'materiales'         => fn ($q) => $q->whereNull('clase_id')->orderByDesc('fecha_publicacion'),
            'planes',
        ]);

        $alumnos = $curso->alumnosViaPlanes()->with('user')->orderBy('id')->paginate(8, ['*'], 'alumnos');

        return view('cursos.show', compact('curso', 'alumnos'));
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
            'zoom_link'    => ['nullable', 'url', 'max:500'],
            'dias_semana'  => ['nullable', 'array'],
            'dias_semana.*'=> ['in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'hora_inicio'  => ['nullable', 'date_format:H:i'],
            'imagen'        => ['nullable', 'image', 'max:2048'],
            'remove_imagen' => ['nullable', 'boolean'],
            'activo'        => ['nullable', 'boolean'],
        ]);

        $this->cursoService->actualizar($curso, $data, $request->file('imagen'));

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
