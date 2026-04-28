<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->foreignId('tarea_id')
                ->nullable()
                ->after('modulo_id')
                ->constrained('tareas')
                ->nullOnDelete();

            $table->index('tarea_id');
        });
    }

    public function down(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropForeign(['tarea_id']);
            $table->dropIndex(['tarea_id']);
            $table->dropColumn('tarea_id');
        });
    }
};
