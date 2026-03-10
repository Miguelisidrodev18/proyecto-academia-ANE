<?php
// database/migrations/xxxx_add_tipo_pago_to_matriculas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->enum('tipo_pago', ['completo', 'mensual', 'cuotas'])
                  ->default('completo')
                  ->after('precio_pagado');
        });
    }

    public function down()
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn('tipo_pago');
        });
    }
};