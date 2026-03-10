<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'titulo', 'mensaje', 'tipo', 'leido', 'leido_at'];

    protected $casts = ['leido' => 'boolean', 'leido_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function marcarLeido(): void
    {
        $this->update(['leido' => true, 'leido_at' => now()]);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leido', false);
    }
}
