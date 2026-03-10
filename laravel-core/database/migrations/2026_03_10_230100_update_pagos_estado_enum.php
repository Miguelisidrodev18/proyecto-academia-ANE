<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrar datos existentes al nuevo esquema
        DB::table('pagos')->where('estado', 'verificado')->update(['estado' => 'confirmado']);
        DB::table('pagos')->where('estado', 'rechazado')->update(['estado' => 'anulado']);

        DB::statement("ALTER TABLE pagos MODIFY COLUMN estado ENUM('confirmado','pendiente','anulado') NOT NULL DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::table('pagos')->where('estado', 'confirmado')->update(['estado' => 'verificado']);
        DB::table('pagos')->where('estado', 'anulado')->update(['estado' => 'rechazado']);

        DB::statement("ALTER TABLE pagos MODIFY COLUMN estado ENUM('pendiente','verificado','rechazado') NOT NULL DEFAULT 'pendiente'");
    }
};
