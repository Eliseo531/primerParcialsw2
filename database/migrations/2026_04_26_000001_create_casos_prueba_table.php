<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('casos_prueba', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->text('condiciones')->nullable();
            $table->text('resultado_esperado');
            $table->unsignedBigInteger('proyecto_id');
            $table->foreignId('creado_por')->constrained('usuarios')->restrictOnDelete();
            $table->timestamps();

            $table->index('proyecto_id');
            $table->index('creado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casos_prueba');
    }
};
