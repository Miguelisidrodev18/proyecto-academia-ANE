<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // JSON: ["lunes","miercoles","viernes"]
            $table->json('dias_semana')->nullable()->after('zoom_link');
            // Hora de inicio de la sesión (para mostrar al alumno)
            $table->time('hora_inicio')->nullable()->after('dias_semana');
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn(['dias_semana', 'hora_inicio']);
        });
    }
};
