<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->unsignedTinyInteger('numero');
            $table->decimal('monto', 8, 2);
            $table->date('fecha_vencimiento');
            $table->enum('estado', ['pendiente', 'pagada', 'vencida'])->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->timestamps();

            $table->unique(['matricula_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
