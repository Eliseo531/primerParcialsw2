<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ejecucion_bug', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ejecucion_id')->constrained('ejecuciones_prueba')->cascadeOnDelete();
            $table->foreignId('bug_id')->constrained('bugs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejecucion_bug');
    }
};
