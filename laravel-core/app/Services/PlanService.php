<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PlanService
{
    public function crear(array $data): Plan
    {
        return DB::transaction(function () use ($data) {
            $plan = Plan::create([
                'nombre'           => $data['nombre'],
                'precio'           => $data['precio'],
                'duracion_meses'   => $data['duracion_meses'],
                'acceso_ilimitado' => (bool) ($data['acceso_ilimitado'] ?? false),
                'descripcion'      => $data['descripcion'] ?? null,
                'activo'           => (bool) ($data['activo'] ?? true),
            ]);

            if (! empty($data['cursos'])) {
                $plan->cursos()->sync($data['cursos']);
            }

            return $plan;
        });
    }

    public function actualizar(Plan $plan, array $data): void
    {
        DB::transaction(function () use ($plan, $data) {
            $plan->update([
                'nombre'           => $data['nombre'],
                'precio'           => $data['precio'],
                'duracion_meses'   => $data['duracion_meses'],
                'acceso_ilimitado' => (bool) ($data['acceso_ilimitado'] ?? false),
                'descripcion'      => $data['descripcion'] ?? null,
                'activo'           => (bool) ($data['activo'] ?? false),
            ]);

            $plan->cursos()->sync($data['cursos'] ?? []);
        });
    }

    public function eliminar(Plan $plan): void
    {
        DB::transaction(function () use ($plan) {
            $plan->delete();
        });
    }

    public function toggleActivo(Plan $plan): bool
    {
        $plan->update(['activo' => ! $plan->activo]);
        return $plan->activo;
    }
}
