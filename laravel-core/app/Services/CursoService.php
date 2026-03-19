<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Support\Facades\DB;

class CursoService
{
    public function crear(array $data): Curso
    {
        return DB::transaction(function () use ($data) {
            return Curso::create([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null,
                'nivel'       => $data['nivel'],
                'grado'       => $data['grado'] ?? null,
                'tipo'        => $data['tipo'],
                'activo'      => (bool) ($data['activo'] ?? true),
            ]);
        });
    }

    public function actualizar(Curso $curso, array $data): void
    {
        DB::transaction(function () use ($curso, $data) {
            $curso->update([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null,
                'nivel'       => $data['nivel'],
                'grado'       => ($data['nivel'] === 'pollito') ? ($data['grado'] ?? null) : null,
                'tipo'        => $data['tipo'],
                'activo'      => (bool) ($data['activo'] ?? false),
            ]);
        });
    }

    public function eliminar(Curso $curso): void
    {
        DB::transaction(function () use ($curso) {
            $curso->delete();
        });
    }

    public function toggleActivo(Curso $curso): bool
    {
        $curso->update(['activo' => ! $curso->activo]);
        return $curso->activo;
    }
}
