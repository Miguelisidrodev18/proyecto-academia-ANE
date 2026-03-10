<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@academianueva.pe'],
            [
                'name'     => 'Administrador',
                'role'     => 'admin',
                'password' => Hash::make('Academia2026!'),
            ]
        );

        // Datos maestros del sistema
        $this->call([
            PlanSeeder::class,
            CursoSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
