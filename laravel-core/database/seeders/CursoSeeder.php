<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = [
            // ── Nivel Pollito (nivelación secundaria) ──────────────────────────
            ['nombre' => 'Matemática Básica',      'descripcion' => 'Aritmética, álgebra y geometría para nivel secundaria.', 'nivel' => 'pollito'],
            ['nombre' => 'Comunicación',            'descripcion' => 'Comprensión lectora, gramática y redacción.', 'nivel' => 'pollito'],
            ['nombre' => 'Ciencias Naturales',      'descripcion' => 'Física, química y biología nivel escolar.', 'nivel' => 'pollito'],
            ['nombre' => 'Historia del Perú',       'descripcion' => 'Historia, geografía y educación cívica.', 'nivel' => 'pollito'],

            // ── Nivel Intermedio (preuniversitario) ────────────────────────────
            ['nombre' => 'Matemática Preuni',       'descripcion' => 'Álgebra, trigonometría, geometría analítica y cálculo básico.', 'nivel' => 'intermedio'],
            ['nombre' => 'Física',                  'descripcion' => 'Mecánica, termodinámica, electricidad y óptica.', 'nivel' => 'intermedio'],
            ['nombre' => 'Química',                 'descripcion' => 'Química general, orgánica e inorgánica.', 'nivel' => 'intermedio'],
            ['nombre' => 'Biología',                'descripcion' => 'Biología celular, genética y ecología.', 'nivel' => 'intermedio'],
            ['nombre' => 'Literatura',              'descripcion' => 'Literatura peruana y universal, análisis de textos.', 'nivel' => 'intermedio'],
            ['nombre' => 'Historia Universal',      'descripcion' => 'Historia, geografía y economía para exámenes de admisión.', 'nivel' => 'intermedio'],
            ['nombre' => 'Razonamiento Verbal',     'descripcion' => 'Analogías, sinónimos, antónimos y comprensión de lectura.', 'nivel' => 'intermedio'],
            ['nombre' => 'Razonamiento Matemático', 'descripcion' => 'Lógica, estadística y resolución de problemas.', 'nivel' => 'intermedio'],

            // ── Ambos niveles ──────────────────────────────────────────────────
            ['nombre' => 'Inglés',                  'descripcion' => 'Inglés básico e intermedio.', 'nivel' => 'ambos'],
            ['nombre' => 'Psicología',              'descripcion' => 'Psicología general para exámenes de admisión.', 'nivel' => 'ambos'],
        ];

        foreach ($cursos as $curso) {
            Curso::firstOrCreate(
                ['nombre' => $curso['nombre']],
                array_merge($curso, ['activo' => true])
            );
        }
    }
}
