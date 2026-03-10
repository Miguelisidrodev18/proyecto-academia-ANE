<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->decimal('precio_pagado', 10, 2)
                ->after('plan_id')     // ajusta la posición si tu esquema es distinto
                ->comment('Precio que el alumno pagó al momento de matricularse');
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn('precio_pagado');
        });
    }
};