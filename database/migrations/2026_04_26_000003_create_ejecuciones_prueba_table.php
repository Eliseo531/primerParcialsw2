<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ejecuciones_prueba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caso_prueba_id')->constrained('casos_prueba')->cascadeOnDelete();
            $table->foreignId('ejecutado_por')->constrained('usuarios')->restrictOnDelete();
            $table->string('resultado', 10); // OK | FAIL
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_ejecucion')->useCurrent();
            $table->timestamps();

            $table->index('caso_prueba_id');
            $table->index('ejecutado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejecuciones_prueba');
    }
};
