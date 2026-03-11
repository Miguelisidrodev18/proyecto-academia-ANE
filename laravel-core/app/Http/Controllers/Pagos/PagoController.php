<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pago\StorePagoRequest;
use App\Http\Requests\Pago\UpdatePagoRequest;
use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PagoController extends Controller
{
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
        $matriculas = Matricula::with(['alumno.user', 'plan', 'pagos'])
            ->whereIn('estado', ['activa', 'pendiente'])
            ->orderBy('id', 'desc')
            ->get();

        $matriculasJson = $matriculas->map(fn ($m) => [
            'id'            => $m->id,
            'label'         => $m->alumno->nombreCompleto() . ' — ' . $m->plan->nombre . ' (DNI: ' . $m->alumno->dni . ')',
            'precio_pagado' => (float) $m->precio_pagado,
            'total_pagado'  => (float) $m->pagos->where('estado', 'confirmado')->sum('monto'),
            'saldo'         => (float) ($m->precio_pagado - $m->pagos->where('estado', 'confirmado')->sum('monto')),
        ])->toJson();

        $matriculaSeleccionada = $request->filled('matricula_id')
            ? Matricula::with(['alumno.user', 'plan'])->find($request->matricula_id)
            : null;

        return view('pagos.create', compact('matriculasJson', 'matriculaSeleccionada'));
    }

    public function store(StorePagoRequest $request): RedirectResponse
    {
        $data      = $request->validated();
        $matricula = Matricula::with('pagos')->findOrFail($data['matricula_id']);
        $saldo     = (float) ($matricula->precio_pagado - $matricula->pagos->where('estado', 'confirmado')->sum('monto'));

        if ($saldo > 0 && (float) $data['monto'] > $saldo) {
            return back()
                ->withErrors(['monto' => 'El monto supera el saldo pendiente de S/. ' . number_format($saldo, 2) . '.'])
                ->withInput();
        }

        $comprobanteUrl = null;
        if ($request->hasFile('comprobante')) {
            $comprobanteUrl = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago = Pago::create([
            'matricula_id'    => $data['matricula_id'],
            'user_id'         => auth()->id(),
            'monto'           => $data['monto'],
            'metodo_pago'     => $data['metodo_pago'],
            'estado'          => $data['estado'],
            'fecha_pago'      => $data['fecha_pago'],
            'referencia'      => $data['referencia'] ?? null,
            'notas'           => $data['notas'] ?? null,
            'comprobante_url' => $comprobanteUrl,
        ]);

        return redirect()->route('pagos.show', $pago)
            ->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago): View
    {
        $pago->load(['matricula.alumno.user', 'matricula.plan', 'matricula.pagos', 'user']);

        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago): View
    {
        $pago->load(['matricula.alumno.user', 'matricula.plan', 'matricula.pagos']);

        $saldoDisponible = (float) (
            $pago->matricula->precio_pagado -
            $pago->matricula->pagos
                ->where('estado', 'confirmado')
                ->where('id', '!=', $pago->id)
                ->sum('monto')
        );

        return view('pagos.edit', compact('pago', 'saldoDisponible'));
    }

    public function update(UpdatePagoRequest $request, Pago $pago): RedirectResponse
    {
        $data = $request->validated();
        unset($data['comprobante']); // manejado por separado

        $pago->load('matricula.pagos');

        $saldoDisponible = (float) (
            $pago->matricula->precio_pagado -
            $pago->matricula->pagos
                ->where('estado', 'confirmado')
                ->where('id', '!=', $pago->id)
                ->sum('monto')
        );

        if ($saldoDisponible > 0 && (float) $data['monto'] > $saldoDisponible) {
            return back()
                ->withErrors(['monto' => 'El monto supera el saldo disponible de S/. ' . number_format($saldoDisponible, 2) . '.'])
                ->withInput();
        }

        if ($request->hasFile('comprobante')) {
            if ($pago->comprobante_url) {
                Storage::disk('public')->delete($pago->comprobante_url);
            }
            $data['comprobante_url'] = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago->update($data);

        return redirect()->route('pagos.show', $pago)
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->update(['estado' => 'anulado']);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago anulado correctamente.');
    }
}
