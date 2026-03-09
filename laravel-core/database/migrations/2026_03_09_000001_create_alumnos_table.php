<?php
// database/migrations/xxxx_create_alumnos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->string('dni', 8)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos',100);
            $table->string('email')->unique();
            $table->string('telefono', 9)->nullable();
            $table->enum('tipo', ['premium', 'vip'])->default('premium');
            $table->date('fecha_nacimiento')->nullable();
            $table->text('direccion')->nullable();
            $table->json('documentos')->nullable();
            $table->string('colegio_procedencia')->nullable();
            $table->integer('anio_egreso')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
};