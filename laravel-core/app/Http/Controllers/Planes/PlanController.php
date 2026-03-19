<?php

namespace App\Http\Controllers\Planes;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function __construct(private PlanService $planService) {}

    public function index(): View
    {
        $planes = Plan::withCount([
            'matriculas as alumnos_activos_count' => fn ($q) =>
                $q->where('estado', 'activa')->where('fecha_fin', '>=', now()),
        ])->orderBy('precio')->get();

        $stats = [
            'total'           => $planes->count(),
            'activos'         => $planes->where('activo', true)->count(),
            'alumnos_activos' => $planes->sum('alumnos_activos_count'),
            'ingreso_potencial' => $planes->where('activo', true)->sum('precio'),
        ];

        return view('planes.index', compact('planes', 'stats'));
    }

    public function create(): View
    {
        return view('planes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:100'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'duracion_meses'   => ['required', 'integer', 'min:1', 'max:36'],
            'acceso_ilimitado' => ['nullable', 'boolean'],
            'descripcion'      => ['nullable', 'string', 'max:500'],
            'activo'           => ['nullable', 'boolean'],
        ]);

        $plan = $this->planService->crear($data);

        return redirect()->route('planes.show', $plan)
            ->with('success', 'Plan "' . $plan->nombre . '" creado correctamente.');
    }

    public function show(Plan $plan): View
    {
        $plan->load(['matriculas.alumno.user']);

        $matriculasActivas = $plan->matriculas()
            ->with('alumno.user')
            ->where('estado', 'activa')
            ->where('fecha_fin', '>=', now())
            ->latest()
            ->get();

        return view('planes.show', compact('plan', 'matriculasActivas'));
    }

    public function edit(Plan $plan): View
    {
        return view('planes.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:100'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'duracion_meses'   => ['required', 'integer', 'min:1', 'max:36'],
            'acceso_ilimitado' => ['nullable', 'boolean'],
            'descripcion'      => ['nullable', 'string', 'max:500'],
            'activo'           => ['nullable', 'boolean'],
        ]);

        $this->planService->actualizar($plan, $data);

        return redirect()->route('planes.show', $plan)
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->matriculas()->exists()) {
            return back()->with('error', 'No se puede eliminar el plan porque tiene matrículas asociadas.');
        }

        $nombre = $plan->nombre;
        $this->planService->eliminar($plan);

        return redirect()->route('planes.index')
            ->with('success', 'Plan "' . $nombre . '" eliminado correctamente.');
    }

    public function toggle(Plan $plan): RedirectResponse
    {
        $activo = $this->planService->toggleActivo($plan);
        $estado = $activo ? 'activado' : 'desactivado';

        return back()->with('success', 'Plan "' . $plan->nombre . '" ' . $estado . ' correctamente.');
    }
}
