<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Matricula\StoreMatriculaRequest;
use App\Http\Requests\Matricula\UpdateMatriculaRequest;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\Plan;
use App\Services\MatriculaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatriculaController extends Controller
{
    public function __construct(private MatriculaService $matriculaService) {}

    public function index(Request $request): View
    {
        $query = Matricula::with(['alumno.user', 'plan'])->latest();

        if ($request->filled('buscar')) {
            $texto = $request->buscar;
            $query->where(function ($q) use ($texto) {
                $q->whereHas('alumno.user', fn ($u) =>
                    $u->where('name', 'like', "%{$texto}%")
                )->orWhereHas('alumno', fn ($a) =>
                    $a->where('dni', 'like', "%{$texto}%")
                );
            });
        }

        if ($request->filled('estado') && in_array($request->estado, ['activa', 'vencida', 'suspendida', 'pendiente'])) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        // Auto-vencer matrículas activas cuya fecha_fin ya pasó
        Matricula::where('estado', 'activa')
            ->whereNotNull('fecha_fin')
            ->where('fecha_fin', '<', now()->startOfDay())
            ->update(['estado' => 'vencida']);

        $matriculas = $query->paginate(15)->withQueryString();
        $planes     = Plan::where('activo', true)->orderBy('nombre')->get();

        $stats = Matricula::selectRaw('
            COUNT(*) as total,
            SUM(estado = "activa") as activas,
            SUM(estado = "vencida") as vencidas,
            SUM(estado = "pendiente") as pendientes,
            SUM(estado = "suspendida") as suspendidas
        ')->first();

        // Recordatorios: matriculas activas con cuotas vencidas o próximas a vencer (≤7 días)
        $recordatorios = Matricula::with(['alumno.user', 'cuotas'])
            ->where('estado', 'activa')
            ->whereHas('cuotas', fn ($q) =>
                $q->whereIn('estado', ['vencida', 'pendiente'])
                  ->where('fecha_vencimiento', '<=', now()->addDays(7))
            )
            ->get()
            ->map(function ($m) {
                $cuotasPendientes = $m->cuotas->filter(fn ($c) =>
                    in_array($c->estado, ['vencida', 'pendiente']) &&
                    $c->fecha_vencimiento <= now()->addDays(7)
                );
                return [
                    'matricula'  => $m,
                    'nombre'     => $m->alumno?->nombreCompleto() ?? '—',
                    'whatsapp'   => $m->alumno?->whatsapp ?? $m->alumno?->telefono ?? '',
                    'saldo'      => $m->saldoPendiente(),
                    'cuotas'     => $cuotasPendientes->count(),
                    'vencidas'   => $cuotasPendientes->where('estado', 'vencida')->count(),
                ];
            })
            ->filter(fn ($r) => $r['cuotas'] > 0);

        return view('matriculas.index', compact('matriculas', 'planes', 'stats', 'recordatorios'));
    }

    public function create(Request $request): View
    {
        $alumnos = Alumno::with('user')->orderBy('id')->get();
        $planes  = Plan::where('activo', true)->orderBy('nombre')->get();

        $alumnoSeleccionado = $request->filled('alumno_id')
            ? Alumno::with('user')->find($request->alumno_id)
            : null;

        return view('matriculas.create', compact('alumnos', 'planes', 'alumnoSeleccionado'));
    }

    public function store(StoreMatriculaRequest $request): RedirectResponse
    {
        $flow      = $request->boolean('flow');
        $matricula = $this->matriculaService->crear($request->validated());

        if ($flow) {
            return redirect()->route('pagos.create', [
                'matricula_id' => $matricula->id,
                'flow'         => 1,
            ])->with('success', 'Matrícula registrada. Ahora registra el primer pago.');
        }

        return redirect()->route('matriculas.show', $matricula)
            ->with('success', 'Matrícula registrada correctamente.');
    }

    public function show(Matricula $matricula): View
    {
        $matricula->load(['alumno.user', 'plan', 'pagos.cuota', 'cuotas']);

        return view('matriculas.show', compact('matricula'));
    }

    public function edit(Matricula $matricula): View
    {
        $matricula->load(['alumno.user', 'plan']);
        $planes = Plan::where('activo', true)->orderBy('nombre')->get();

        return view('matriculas.edit', compact('matricula', 'planes'));
    }

    public function update(UpdateMatriculaRequest $request, Matricula $matricula): RedirectResponse
    {
        $this->matriculaService->actualizar($matricula, $request->validated());

        return redirect()->route('matriculas.show', $matricula)
            ->with('success', 'Matrícula actualizada correctamente.');
    }

    public function destroy(Matricula $matricula): RedirectResponse
    {
        $resultado = $this->matriculaService->eliminarOSuspender($matricula);

        $mensaje = $resultado === 'suspendida'
            ? 'La matrícula tiene pagos y fue suspendida en lugar de eliminada.'
            : 'Matrícula eliminada correctamente.';

        return redirect()->route('matriculas.index')->with('success', $mensaje);
    }
}
