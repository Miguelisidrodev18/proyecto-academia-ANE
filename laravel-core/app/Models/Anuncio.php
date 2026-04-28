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
        'activo',
        'orden',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'activo'       => 'boolean',
        'destinatarios'=> 'array',
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
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

    public function imagenUrl(): ?string
    {
        return $this->imagen ? Storage::url($this->imagen) : null;
    }

    public function esParaRol(string $rol): bool
    {
        return in_array($rol, $this->destinatarios ?? []);
    }
}
