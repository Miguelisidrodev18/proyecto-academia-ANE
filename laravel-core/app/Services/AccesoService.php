<?php

namespace App\Services;

use App\Models\Alumno;
use Carbon\Carbon;

class AccesoService
{
    public function verificarAcceso(int $alumnoId): bool
    {
        $alumno = Alumno::find($alumnoId);

        if (! $alumno) {
            return false;
        }

        return $alumno->matriculas()
            ->where('estado', 'activa')
            ->where('fecha_fin', '>=', now())
            ->exists();
    }

    public function registrarAcceso(int $alumnoId): array
    {
        $alumno        = Alumno::findOrFail($alumnoId);
        $hoyStr        = Carbon::today()->toDateString();
        $ayerStr       = Carbon::yesterday()->toDateString();
        $rachaAnterior = (int) ($alumno->racha_actual ?? 0);
        $subioRacha    = false;
        $perdioRacha   = false;
        $mismoDia      = false;

        if ($alumno->ultimo_acceso === null) {
            $alumno->racha_actual = 1;
            $subioRacha           = true;
        } elseif ($alumno->ultimo_acceso->toDateString() === $hoyStr) {
            $mismoDia = true;
        } elseif ($alumno->ultimo_acceso->toDateString() === $ayerStr) {
            $alumno->racha_actual += 1;
            $subioRacha            = true;
        } else {
            $perdioRacha          = true;
            $alumno->racha_actual = 1;
        }

        if (! $mismoDia) {
            $alumno->ultimo_acceso = $hoy;
            $alumno->acceso_activo = true;
            $alumno->save();
        }

        return [
            'racha_actual'    => (int) $alumno->racha_actual,
            'si_subio_racha'  => $subioRacha,
            'si_perdio_racha' => $perdioRacha,
            'racha_anterior'  => $rachaAnterior,
            'mismo_dia'       => $mismoDia,
        ];
    }

    public function calcularRacha(Alumno $alumno): array
    {
        $hoy = Carbon::today();

        if ($alumno->ultimo_acceso === null) {
            return ['racha_actual' => 0, 'activa' => false];
        }

        $diff  = $alumno->ultimo_acceso->diffInDays($hoy);
        $activa = $diff <= 1;

        return [
            'racha_actual' => (int) $alumno->racha_actual,
            'activa'       => $activa,
        ];
    }
}
