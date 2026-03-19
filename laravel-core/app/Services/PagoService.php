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

        // Si el pago ya viene confirmado y tiene cuota, marcarla pagada
        if ($pago->estado === 'confirmado' && $pago->cuota_id) {
            $cuota = Cuota::find($pago->cuota_id);
            $cuota?->update(['estado' => 'pagada', 'fecha_pago' => Carbon::parse($pago->fecha_pago)]);
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

        $pago->update($data);
    }

    public function confirmarPago(Pago $pago): void
    {
        $pago->update(['estado' => 'confirmado']);

        if ($pago->cuota_id) {
            Cuota::where('id', $pago->cuota_id)
                ->update(['estado' => 'pagada', 'fecha_pago' => $pago->fecha_pago]);
        }
    }

    public function anularPago(Pago $pago): void
    {
        $pago->update(['estado' => 'anulado']);

        // Revertir cuota a pendiente si aplica
        if ($pago->cuota_id) {
            Cuota::where('id', $pago->cuota_id)
                ->where('estado', 'pagada')
                ->update(['estado' => 'pendiente', 'fecha_pago' => null]);
        }
    }
}
