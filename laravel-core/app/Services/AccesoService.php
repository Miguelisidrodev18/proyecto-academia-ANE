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

    public function registrarAcceso(int $alumnoId): void
    {
        $alumno = Alumno::findOrFail($alumnoId);

        $this->actualizarRacha($alumnoId);

        $alumno->update([
            'acceso_activo' => true,
            'ultimo_acceso' => Carbon::today(),
        ]);
    }

    public function actualizarRacha(int $alumnoId): void
    {
        $alumno = Alumno::findOrFail($alumnoId);
        $hoy    = Carbon::today();
        $ayer   = Carbon::yesterday();

        if ($alumno->ultimo_acceso === null) {
            $alumno->racha_actual = 1;
        } elseif ($alumno->ultimo_acceso->equalTo($ayer)) {
            $alumno->racha_actual += 1;
        } elseif ($alumno->ultimo_acceso->equalTo($hoy)) {
            return;
        } else {
            $alumno->racha_actual = 1;
        }

        $alumno->ultimo_acceso = $hoy;
        $alumno->save();
    }
}
