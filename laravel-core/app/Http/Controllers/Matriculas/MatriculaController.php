<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Matricula\StoreMatriculaRequest;
use App\Http\Requests\Matricula\UpdateMatriculaRequest;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MatriculaController extends Controller
{
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

        $matriculas = $query->paginate(15)->withQueryString();
        $planes     = Plan::where('activo', true)->orderBy('nombre')->get();

        $stats = Matricula::selectRaw('
            COUNT(*) as total,
            SUM(estado = "activa") as activas,
            SUM(estado = "vencida") as vencidas,
            SUM(estado = "pendiente") as pendientes,
            SUM(estado = "suspendida") as suspendidas
        ')->first();

        return view('matriculas.index', compact('matriculas', 'planes', 'stats'));
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
        $data = $request->validated();

        $matricula = DB::transaction(function () use ($data) {
            $plan         = Plan::findOrFail($data['plan_id']);
            $fechaInicio  = Carbon::parse($data['fecha_inicio']);
            $diasCortesia = (int) ($data['dias_cortesia'] ?? 0);
            $fechaFin     = $fechaInicio->copy()->addMonths($plan->duracion_meses)->addDays($diasCortesia);

            return Matricula::create([
                'alumno_id'     => $data['alumno_id'],
                'plan_id'       => $plan->id,
                'precio_pagado' => $plan->precio,
                'tipo_pago'     => $data['tipo_pago'],
                'fecha_inicio'  => $fechaInicio,
                'fecha_fin'     => $fechaFin,
                'estado'        => 'activa',
                'dias_cortesia' => $diasCortesia,
                'observaciones' => $data['observaciones'] ?? null,
            ]);
        });

        return redirect()->route('matriculas.show', $matricula)
            ->with('success', 'Matrícula registrada correctamente.');
    }

    public function show(Matricula $matricula): View
    {
        $matricula->load(['alumno.user', 'plan', 'pagos']);

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
        $data = $request->validated();

        DB::transaction(function () use ($data, $matricula) {
            $plan         = Plan::findOrFail($data['plan_id']);
            $fechaInicio  = Carbon::parse($data['fecha_inicio']);
            $diasCortesia = (int) ($data['dias_cortesia'] ?? 0);
            $fechaFin     = $fechaInicio->copy()
                ->addMonths($plan->duracion_meses)
                ->addDays($diasCortesia);

            $matricula->update([
                'plan_id'       => $plan->id,
                'precio_pagado' => $plan->precio,
                'fecha_inicio'  => $fechaInicio,
                'fecha_fin'     => $fechaFin,
                'tipo_pago'     => $data['tipo_pago'],
                'dias_cortesia' => $diasCortesia,
                'observaciones' => $data['observaciones'] ?? null,
                'estado'        => $data['estado'],
            ]);
        });

        return redirect()->route('matriculas.show', $matricula)
            ->with('success', 'Matrícula actualizada correctamente.');
    }

    public function destroy(Matricula $matricula): RedirectResponse
    {
        if ($matricula->pagos()->exists()) {
            $matricula->update(['estado' => 'suspendida']);

            return redirect()->route('matriculas.index')
                ->with('success', 'La matrícula tiene pagos y fue suspendida en lugar de eliminada.');
        }

        $matricula->delete();

        return redirect()->route('matriculas.index')
            ->with('success', 'Matrícula eliminada correctamente.');
    }
}
