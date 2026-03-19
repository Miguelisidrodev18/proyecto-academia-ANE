<?php

namespace App\Services;

use App\Models\Alerta;

class AlertaService
{
    public function crear(int $userId, string $titulo, string $mensaje, string $tipo = 'info'): Alerta
    {
        return Alerta::create([
            'user_id' => $userId,
            'titulo'  => $titulo,
            'mensaje' => $mensaje,
            'tipo'    => $tipo,
            'leido'   => false,
        ]);
    }

    public function marcarLeida(int $alertaId): void
    {
        Alerta::findOrFail($alertaId)->marcarLeido();
    }

    public function marcarTodasLeidas(int $userId): int
    {
        return Alerta::where('user_id', $userId)
            ->noLeidas()
            ->update(['leido' => true, 'leido_at' => now()]);
    }

    public function contarNoLeidas(int $userId): int
    {
        return Alerta::where('user_id', $userId)->noLeidas()->count();
    }
}
