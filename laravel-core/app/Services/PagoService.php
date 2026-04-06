<?php

namespace App\Services;

use App\Models\Cuota;
use App\Models\Matricula;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PagoService
{
    public function calcularSaldoPendiente(Matricula $matricula, ?int $excluirPagoId = null): float
    {
        $totalPagado = $matricula->pagos()
            ->where('estado', 'confirmado')
            ->when($excluirPagoId, fn ($q) => $q->where('id', '!=', $excluirPagoId))
            ->sum('monto');

        return (float) ($matricula->precio_pagado - $totalPagado);
    }

    public function procesarPago(array $data, ?UploadedFile $comprobante = null): Pago
    {
        $comprobanteUrl = null;
        if ($comprobante) {
            $comprobanteUrl = $comprobante->store('comprobantes', 'public');
        }

        $pago = Pago::create([
            'matricula_id'    => $data['matricula_id'],
            'cuota_id'        => $data['cuota_id'] ?? null,
            'user_id'         => $data['user_id'],
            'monto'           => $data['monto'],
            'metodo_pago'     => $data['metodo_pago'],
            'estado'          => $data['estado'],
            'fecha_pago'      => $data['fecha_pago'],
            'referencia'      => $data['referencia'] ?? null,
            'notas'           => $data['notas'] ?? null,
            'comprobante_url' => $comprobanteUrl,
        ]);

        if ($pago->estado === 'confirmado') {
            $this->reconciliarCuotas(Matricula::find($pago->matricula_id), Carbon::parse($pago->fecha_pago));
        }

        return $pago;
    }

    public function actualizarPago(Pago $pago, array $data, ?UploadedFile $comprobante = null): void
    {
        if ($comprobante) {
            if ($pago->comprobante_url) {
                Storage::disk('public')->delete($pago->comprobante_url);
            }
            $data['comprobante_url'] = $comprobante->store('comprobantes', 'public');
        }

        $estadoAnterior = $pago->estado;
        $pago->update($data);

        if ($pago->fresh()->estado === 'confirmado') {
            $this->reconciliarCuotas($pago->matricula, Carbon::parse($pago->fecha_pago));
        } elseif ($estadoAnterior === 'confirmado' && $pago->fresh()->estado !== 'confirmado') {
            $this->reconciliarCuotas($pago->matricula);
        }
    }

    public function confirmarPago(Pago $pago): void
    {
        $pago->update(['estado' => 'confirmado']);
        $this->reconciliarCuotas($pago->matricula, Carbon::parse($pago->fecha_pago));
    }

    public function anularPago(Pago $pago): void
    {
        $pago->update(['estado' => 'anulado']);
        $this->reconciliarCuotas($pago->matricula);
    }

    /**
     * Reconcilia el estado de las cuotas según el total pagado confirmado.
     * Recorre las cuotas en orden y las marca como 'pagada' mientras
     * el acumulado de pagos confirmados cubra su monto.
     * Las cuotas no cubiertas se revierten a 'pendiente' o 'vencida'.
     */
    public function reconciliarCuotas(Matricula $matricula, ?Carbon $fechaPago = null): void
    {
        $totalPagado = (float) $matricula->pagos()
            ->where('estado', 'confirmado')
            ->sum('monto');

        $cuotas = $matricula->cuotas()->orderBy('numero')->get();
        $acumulado = 0.0;

        foreach ($cuotas as $cuota) {
            $acumulado += (float) $cuota->monto;

            if ($totalPagado >= $acumulado - 0.01) {
                // El pago cubre esta cuota → marcar pagada
                if ($cuota->estado !== 'pagada') {
                    $cuota->update([
                        'estado'     => 'pagada',
                        'fecha_pago' => $fechaPago ?? now(),
                    ]);
                }
            } else {
                // No cubierta → revertir a pendiente o vencida según fecha
                if ($cuota->estado === 'pagada') {
                    $estaVencida = $cuota->fecha_vencimiento->lt(now()->startOfDay());
                    $cuota->update([
                        'estado'     => $estaVencida ? 'vencida' : 'pendiente',
                        'fecha_pago' => null,
                    ]);
                }
            }
        }
    }
}
