<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('email', 150);
            $table->string('telefono', 20)->nullable();
            $table->enum('nivel', ['pollito', 'intermedio', 'no_sabe'])->default('no_sabe');
            $table->foreignId('plan_id')->nullable()->constrained('planes')->nullOnDelete();
            $table->text('mensaje')->nullable();
            $table->enum('estado', ['nuevo', 'contactado', 'matriculado', 'descartado'])->default('nuevo');
            $table->text('notas_admin')->nullable();
            $table->string('origen', 50)->default('landing');
            $table->timestamp('contactado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
