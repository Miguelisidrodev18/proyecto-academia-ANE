<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            [
                'nombre'           => 'Pollito Mensual',
                'precio'           => 150.00,
                'duracion_meses'   => 1,
                'acceso_ilimitado' => false,
                'descripcion'      => 'Plan nivelación mensual para alumnos de 1° a 5° secundaria.',
                'activo'           => true,
            ],
            [
                'nombre'           => 'Pollito Semestral',
                'precio'           => 750.00,
                'duracion_meses'   => 6,
                'acceso_ilimitado' => false,
                'descripcion'      => 'Plan nivelación semestral con descuento incluido.',
                'activo'           => true,
            ],
            [
                'nombre'           => 'Intermedio Mensual',
                'precio'           => 200.00,
                'duracion_meses'   => 1,
                'acceso_ilimitado' => false,
                'descripcion'      => 'Plan preuniversitario mensual, preparación para exámenes de admisión.',
                'activo'           => true,
            ],
            [
                'nombre'           => 'Intermedio Semestral',
                'precio'           => 1000.00,
                'duracion_meses'   => 6,
                'acceso_ilimitado' => false,
                'descripcion'      => 'Plan preuniversitario semestral con acceso completo al material.',
                'activo'           => true,
            ],
            [
                'nombre'           => 'Intermedio Anual',
                'precio'           => 1800.00,
                'duracion_meses'   => 12,
                'acceso_ilimitado' => true,
                'descripcion'      => 'Plan preuniversitario anual con acceso ilimitado a todos los cursos y materiales.',
                'activo'           => true,
            ],
        ];

        foreach ($planes as $plan) {
            Plan::firstOrCreate(['nombre' => $plan['nombre']], $plan);
        }
    }
}
