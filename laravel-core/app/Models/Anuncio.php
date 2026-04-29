<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Anuncio extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'link_url',
        'link_texto',
        'tipo_link',
        'destinatarios',
        'planes_ids',
        'activo',
        'orden',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'destinatarios' => 'array',
        'planes_ids'    => 'array',
        'fecha_inicio'  => 'date',
        'fecha_fin'     => 'date',
    ];

    public function scopeVigentes($query)
    {
        return $query->where('activo', true)
            ->where(fn ($q) => $q->whereNull('fecha_inicio')->orWhereDate('fecha_inicio', '<=', now()))
            ->where(fn ($q) => $q->whereNull('fecha_fin')->orWhereDate('fecha_fin', '>=', now()));
    }

    public function scopeParaRol($query, string $rol)
    {
        return $query->whereJsonContains('destinatarios', $rol);
    }

    public function scopeParaPlan($query, ?int $planId)
    {
        if (!$planId) return $query;
        return $query->where(function ($q) use ($planId) {
            $q->whereNull('planes_ids')
              ->orWhereJsonLength('planes_ids', 0)
              ->orWhereJsonContains('planes_ids', $planId);
        });
    }

    public function imagenUrl(): ?string
    {
        if (!$this->imagen) return null;
        if (!Storage::disk('public')->exists($this->imagen)) return null;
        return asset('storage/' . $this->imagen);
    }

    public function esParaRol(string $rol): bool
    {
        return in_array($rol, $this->destinatarios ?? []);
    }
}
