<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'imagen_url', 'activo'];

    protected $casts = ['activo' => 'boolean', 'precio' => 'decimal:2'];

    public function detallePedidos(): HasMany { return $this->hasMany(DetallePedido::class); }

    public function hayStock(): bool           { return $this->stock > 0; }
    public function precioFormateado(): string { return 'S/. ' . number_format($this->precio, 2); }
}
