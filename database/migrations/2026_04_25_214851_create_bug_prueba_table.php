<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bug_prueba', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bug_id')
                ->constrained('bugs')
                ->cascadeOnDelete();

            $table->foreignId('ejecucion_prueba_id')
                ->constrained('ejecuciones_prueba')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['bug_id', 'ejecucion_prueba_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_prueba');
    }
};
