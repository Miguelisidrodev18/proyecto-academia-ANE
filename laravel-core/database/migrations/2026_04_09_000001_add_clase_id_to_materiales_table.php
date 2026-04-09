<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->foreignId('clase_id')
                  ->nullable()
                  ->after('curso_id')
                  ->constrained('clases')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->dropForeign(['clase_id']);
            $table->dropColumn('clase_id');
        });
    }
};
