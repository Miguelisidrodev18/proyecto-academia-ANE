<?php

namespace App\Services;

use App\Models\Cuota;
use App\Models\Matricula;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CuotaService
{
    private const CUOTAS_DEFAULT = 3;

    // ── Generación ────────────────────────────────────────────────────────────

    public function generarCuotas(Matricula $matricula): Collection
    {
        return DB::transaction(function () use ($matricula) {
            $matricula->cuotas()->delete();

            $cuotas = match ($matricula->tipo_pago) {
                'completo' => $this->cuotasCompleto($matricula),
                'mensual'  => $this->cuotasMensual($matricula),
                'cuotas'   => $this->cuotasPorCuotas($matricula),
                default    => collect(),
            };

            return $cuotas;
        });
    }

    private function cuotasCompleto(Matricula $matricula): Collection
    {
        $cuota = Cuota::create([
            'matricula_id'      => $matricula->id,
            'numero'            => 1,
            'monto'             => $matricula->precio_pagado,
            'fecha_vencimiento' => $matricula->fecha_inicio->copy()->addDays(3),
            'estado'            => 'pendiente',
        ]);

        return collect([$cuota]);
    }

    private function cuotasMensual(Matricula $matricula): Collection
    {
        $plan    = $matricula->plan;
        $meses   = $plan->duracion_meses;
        $monto   = round($matricula->precio_pagado / $meses, 2);
        $base    = $matricula->fecha_inicio->copy();
        $cuotas  = collect();

        for ($i = 0; $i < $meses; $i++) {
            $cuota = Cuota::create([
                'matricula_id'      => $matricula->id,
                'numero'            => $i + 1,
                'monto'             => $monto,
                'fecha_vencimiento' => $base->copy()->addMonths($i)->addDays(5),
                'estado'            => 'pendiente',
            ]);
            $cuotas->push($cuota);
        }

        return $cuotas;
    }

    private function cuotasPorCuotas(Matricula $matricula): Collection
    {
        $total   = (float) $matricula->precio_pagado;
        $n       = self::CUOTAS_DEFAULT;
        $monto   = round($total / $n, 2);
        $base    = $matricula->fecha_inicio->copy();
        $cuotas  = collect();

        for ($i = 0; $i < $n; $i++) {
            $cuota = Cuota::create([
                'matricula_id'      => $matricula->id,
                'numero'            => $i + 1,
                'monto'             => $monto,
                'fecha_vencimiento' => $base->copy()->addMonths($i)->addDays(5),
                'estado'            => 'pendiente',
            ]);
            $cuotas->push($cuota);
        }

        return $cuotas;
    }

    // ── Pago de cuota ─────────────────────────────────────────────────────────

    public function marcarPagada(Cuota $cuota, Carbon $fechaPago): void
    {
        $cuota->update([
            'estado'     => 'pagada',
            'fecha_pago' => $fechaPago,
        ]);
    }

    public function marcarPendiente(Cuota $cuota): void
    {
        $cuota->update([
            'estado'     => 'pendiente',
            'fecha_pago' => null,
        ]);
    }

    // ── Proceso automático (scheduler) ────────────────────────────────────────

    public function vencerCuotasExpiradas(): int
    {
        return Cuota::where('estado', 'pendiente')
            ->where('fecha_vencimiento', '<', now()->startOfDay())
            ->update(['estado' => 'vencida']);
    }

    public function suspenderMatriculasConCuotasVencidas(): int
    {
        $count = 0;

        Matricula::where('estado', 'activa')
            ->whereHas('cuotas', fn ($q) => $q->where('estado', 'vencida'))
            ->each(function (Matricula $matricula) use (&$count) {
                $matricula->update(['estado' => 'suspendida']);
                $count++;
            });

        return $count;
    }

    public function procesarCicloCompleto(): array
    {
        return [
            'cuotas_vencidas'        => $this->vencerCuotasExpiradas(),
            'matriculas_suspendidas' => $this->suspenderMatriculasConCuotasVencidas(),
        ];
    }
}
