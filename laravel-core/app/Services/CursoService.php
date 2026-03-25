<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CursoService
{
    public function crear(array $data, ?\Illuminate\Http\UploadedFile $imagen = null): Curso
    {
        return DB::transaction(function () use ($data, $imagen) {
            $imagenUrl = null;
            if ($imagen) {
                $imagenUrl = $imagen->store('cursos', 'public');
            }

            return Curso::create([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null,
                'nivel'       => $data['nivel'],
                'grado'       => $data['grado'] ?? null,
                'tipo'        => $data['tipo'],
                'zoom_link'   => $data['zoom_link'] ?? null,
                'dias_semana' => $data['dias_semana'] ?? null,
                'hora_inicio' => $data['hora_inicio'] ?? null,
                'imagen_url'  => $imagenUrl,
                'activo'      => (bool) ($data['activo'] ?? true),
            ]);
        });
    }

    public function actualizar(Curso $curso, array $data, ?\Illuminate\Http\UploadedFile $imagen = null): void
    {
        DB::transaction(function () use ($curso, $data, $imagen) {
            $imagenUrl = $curso->imagen_url;

            if ($imagen) {
                // Borrar imagen anterior si existe
                if ($imagenUrl) {
                    Storage::disk('public')->delete($imagenUrl);
                }
                $imagenUrl = $imagen->store('cursos', 'public');
            }

            $curso->update([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null,
                'nivel'       => $data['nivel'],
                'grado'       => ($data['nivel'] === 'pollito') ? ($data['grado'] ?? null) : null,
                'tipo'        => $data['tipo'],
                'zoom_link'   => $data['zoom_link'] ?? null,
                'dias_semana' => $data['dias_semana'] ?? null,
                'hora_inicio' => $data['hora_inicio'] ?? null,
                'imagen_url'  => $imagenUrl,
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
