<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {

            $table->boolean('acceso_activo')
                ->default(true)
                ->after('estado');

            $table->integer('racha_actual')
                ->default(0)
                ->after('acceso_activo');

            $table->date('ultimo_acceso')
                ->nullable()
                ->after('racha_actual');

            $table->string('origen_registro')
                ->nullable()
                ->after('telefono');

        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {

            $table->dropColumn([
                'acceso_activo',
                'racha_actual',
                'ultimo_acceso',
                'origen_registro'
            ]);

        });
    }
};