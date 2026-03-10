<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_alumno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->date('fecha_inscripcion')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['alumno_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_alumno');
    }
};
