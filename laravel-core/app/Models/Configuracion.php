<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor'];

    public static function get(string $clave, string $default = ''): string
    {
        try {
            return static::where('clave', $clave)->value('valor') ?? $default;
        } catch (\Exception) {
            return $default;
        }
    }

    public static function set(string $clave, ?string $valor): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
    }
}
