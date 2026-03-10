<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['nombre' => 'Compendio Matemática Preuniversitaria', 'descripcion' => 'Libro completo con teoría y ejercicios resueltos.',        'precio' => 45.00, 'stock' => 50],
            ['nombre' => 'Compendio de Física',                   'descripcion' => 'Teoría, fórmulas y ejercicios de física preuniversitaria.', 'precio' => 40.00, 'stock' => 40],
            ['nombre' => 'Compendio de Química',                  'descripcion' => 'Química general, orgánica e inorgánica.',                   'precio' => 40.00, 'stock' => 35],
            ['nombre' => 'Pack Ciencias (Física+Química+Bio)',    'descripcion' => 'Los tres compendios de ciencias con descuento.',            'precio' => 100.00,'stock' => 20],
            ['nombre' => 'Cuaderno de Práctica - Matemática',     'descripcion' => 'Ejercicios de práctica para el nivel pollito.',             'precio' => 15.00, 'stock' => 100],
            ['nombre' => 'Cuaderno de Práctica - Comunicación',   'descripcion' => 'Ejercicios de comprensión lectora y gramática.',            'precio' => 15.00, 'stock' => 80],
            ['nombre' => 'Separata Razonamiento Verbal',          'descripcion' => 'Separata con ejercicios de admisión universitaria.',        'precio' => 10.00, 'stock' => 60],
            ['nombre' => 'Separata Razonamiento Matemático',      'descripcion' => 'Separata de lógica y resolución de problemas.',             'precio' => 10.00, 'stock' => 60],
            ['nombre' => 'USB con Material Digital',              'descripcion' => 'USB con videos, PDFs y ejercicios interactivos.',           'precio' => 35.00, 'stock' => 30],
            ['nombre' => 'Folder Academia Nueva Era',             'descripcion' => 'Folder institucional con separadores.',                     'precio' => 5.00,  'stock' => 200],
        ];

        foreach ($productos as $producto) {
            Producto::firstOrCreate(
                ['nombre' => $producto['nombre']],
                array_merge($producto, ['activo' => true])
            );
        }
    }
}
