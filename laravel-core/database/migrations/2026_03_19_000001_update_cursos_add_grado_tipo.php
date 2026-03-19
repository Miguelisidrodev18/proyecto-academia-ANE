<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->tinyInteger('grado')->unsigned()->nullable()->after('nivel')
                  ->comment('1-5 para nivel pollito, null para intermedio');
            $table->enum('tipo', ['reforzamiento', 'preuniversitario'])
                  ->default('reforzamiento')->after('grado');
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn(['grado', 'tipo']);
        });
    }
};
