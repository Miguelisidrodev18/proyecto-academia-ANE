<?php

namespace App\Services;

use App\Models\Matricula;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MatriculaService
{
    public function __construct(private CuotaService $cuotaService) {}

    public function calcularFechaFin(Carbon $fechaInicio, int $duracionMeses, int $diasCortesia = 0): Carbon
    {
        return $fechaInicio->copy()->addMonths($duracionMeses)->addDays($diasCortesia);
    }

    public function verificarDuplicada(int $alumnoId): ?Matricula
    {
        return Matricula::with('plan')
            ->where('alumno_id', $alumnoId)
            ->where('estado', 'activa')
            ->where('fecha_fin', '>=', now())
            ->latest()
            ->first();
    }

    public function crear(array $data): Matricula
    {
        return DB::transaction(function () use ($data) {
            $plan         = Plan::findOrFail($data['plan_id']);
            $fechaInicio  = Carbon::parse($data['fecha_inicio']);
            $diasCortesia = (int) ($data['dias_cortesia'] ?? 0);

            $matricula = Matricula::create([
                'alumno_id'     => $data['alumno_id'],
                'plan_id'       => $plan->id,
                'precio_pagado' => $plan->precio,
                'tipo_pago'     => $data['tipo_pago'],
                'fecha_inicio'  => $fechaInicio,
                'fecha_fin'     => $this->calcularFechaFin($fechaInicio, $plan->duracion_meses, $diasCortesia),
                'estado'        => 'activa',
                'dias_cortesia' => $diasCortesia,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $this->cuotaService->generarCuotas($matricula);

            return $matricula;
        });
    }

    public function actualizar(Matricula $matricula, array $data): void
    {
        DB::transaction(function () use ($matricula, $data) {
            $plan         = Plan::findOrFail($data['plan_id']);
            $fechaInicio  = Carbon::parse($data['fecha_inicio']);
            $diasCortesia = (int) ($data['dias_cortesia'] ?? 0);

            $matricula->update([
                'plan_id'       => $plan->id,
                'precio_pagado' => $plan->precio,
                'fecha_inicio'  => $fechaInicio,
                'fecha_fin'     => $this->calcularFechaFin($fechaInicio, $plan->duracion_meses, $diasCortesia),
                'tipo_pago'     => $data['tipo_pago'],
                'dias_cortesia' => $diasCortesia,
                'observaciones' => $data['observaciones'] ?? null,
                'estado'        => $data['estado'],
            ]);
        });
    }

    public function eliminarOSuspender(Matricula $matricula): string
    {
        if ($matricula->pagos()->exists()) {
            $matricula->update(['estado' => 'suspendida']);
            return 'suspendida';
        }

        $matricula->delete();
        return 'eliminada';
    }

    public function vencerMatriculasExpiradas(): int
    {
        return Matricula::where('estado', 'activa')
            ->where('fecha_fin', '<', now())
            ->update(['estado' => 'vencida']);
    }
}
