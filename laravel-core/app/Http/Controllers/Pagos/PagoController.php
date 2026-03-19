<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pago\StorePagoRequest;
use App\Http\Requests\Pago\UpdatePagoRequest;
use App\Models\Cuota;
use App\Models\Matricula;
use App\Models\Pago;
use App\Services\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function __construct(private PagoService $pagoService) {}

    public function index(Request $request): View
    {
        $query = Pago::with(['matricula.alumno.user', 'matricula.plan', 'user'])->latest();

        if ($request->filled('buscar')) {
            $texto = $request->buscar;
            $query->where(function ($q) use ($texto) {
                $q->whereHas('matricula.alumno.user', fn ($u) =>
                    $u->where('name', 'like', "%{$texto}%")
                )->orWhereHas('matricula.alumno', fn ($a) =>
                    $a->where('dni', 'like', "%{$texto}%")
                );
            });
        }

        if ($request->filled('estado') && in_array($request->estado, ['confirmado', 'pendiente', 'anulado'])) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo_pago') && in_array($request->metodo_pago, ['efectivo', 'transferencia', 'yape', 'plin', 'tarjeta', 'mixto'])) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $pagos = $query->paginate(15)->withQueryString();

        $stats = Pago::selectRaw('
            COUNT(*) as total,
            SUM(estado = "confirmado") as confirmados,
            SUM(estado = "pendiente") as pendientes,
            SUM(estado = "anulado") as anulados,
            SUM(CASE WHEN estado = "confirmado" THEN monto ELSE 0 END) as monto_total
        ')->first();

        return view('pagos.index', compact('pagos', 'stats'));
    }

    public function create(Request $request): View
    {
        $matriculas = Matricula::with(['alumno.user', 'plan', 'pagos', 'cuotas'])
            ->whereIn('estado', ['activa', 'pendiente'])
            ->orderBy('id', 'desc')
            ->get();

        $matriculasJson = $matriculas->map(fn ($m) => [
            'id'            => $m->id,
            'label'         => $m->alumno->nombreCompleto() . ' — ' . $m->plan->nombre . ' (DNI: ' . $m->alumno->dni . ')',
            'precio_pagado' => (float) $m->precio_pagado,
            'total_pagado'  => (float) $m->pagos->where('estado', 'confirmado')->sum('monto'),
            'saldo'         => (float) $this->pagoService->calcularSaldoPendiente($m),
            'cuotas'        => $m->cuotas->whereIn('estado', ['pendiente', 'vencida'])->values()->map(fn ($c) => [
                'id'                => $c->id,
                'numero'            => $c->numero,
                'monto'             => (float) $c->monto,
                'estado'            => $c->estado,
                'fecha_vencimiento' => $c->fecha_vencimiento->format('d/m/Y'),
            ]),
        ])->toJson();

        $matriculaSeleccionada = $request->filled('matricula_id')
            ? Matricula::with(['alumno.user', 'plan'])->find($request->matricula_id)
            : null;

        return view('pagos.create', compact('matriculasJson', 'matriculaSeleccionada'));
    }

    public function store(StorePagoRequest $request): RedirectResponse
    {
        $flow      = $request->boolean('flow');
        $data      = $request->validated();
        $matricula = Matricula::findOrFail($data['matricula_id']);
        $saldo     = $this->pagoService->calcularSaldoPendiente($matricula);

        if ($saldo > 0 && (float) $data['monto'] > $saldo) {
            return back()
                ->withErrors(['monto' => 'El monto supera el saldo pendiente de S/. ' . number_format($saldo, 2) . '.'])
                ->withInput();
        }

        $data['user_id'] = auth()->id();
        $pago = $this->pagoService->procesarPago($data, $request->file('comprobante'));

        $mensaje = $flow
            ? '¡Registro completo! Alumno, matrícula y pago registrados correctamente.'
            : 'Pago registrado correctamente.';

        return redirect()->route('pagos.show', $pago)
            ->with('success', $mensaje)
            ->with('flow_complete', $flow);
    }

    public function show(Pago $pago): View
    {
        $pago->load(['matricula.alumno.user', 'matricula.plan', 'matricula.pagos', 'user', 'cuota']);

        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago): View
    {
        $pago->load(['matricula.alumno.user', 'matricula.plan', 'matricula.pagos']);

        $saldoDisponible = $this->pagoService->calcularSaldoPendiente($pago->matricula, $pago->id);

        return view('pagos.edit', compact('pago', 'saldoDisponible'));
    }

    public function update(UpdatePagoRequest $request, Pago $pago): RedirectResponse
    {
        $data = $request->validated();
        unset($data['comprobante']);

        $pago->load('matricula');
        $saldoDisponible = $this->pagoService->calcularSaldoPendiente($pago->matricula, $pago->id);

        if ($saldoDisponible > 0 && (float) $data['monto'] > $saldoDisponible) {
            return back()
                ->withErrors(['monto' => 'El monto supera el saldo disponible de S/. ' . number_format($saldoDisponible, 2) . '.'])
                ->withInput();
        }

        $this->pagoService->actualizarPago($pago, $data, $request->file('comprobante'));

        return redirect()->route('pagos.show', $pago)
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $this->pagoService->anularPago($pago);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago anulado correctamente.');
    }
}
