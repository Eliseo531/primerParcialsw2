<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casos_prueba', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();

            $table->foreignId('modulo_id')
                ->nullable()
                ->constrained('modulos_proyecto')
                ->nullOnDelete();

            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->text('precondiciones')->nullable();
            $table->text('pasos');
            $table->text('resultado_esperado');

            $table->foreignId('creado_por')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->string('estado', 20)->default('activo');

            $table->timestamps();

            $table->index('proyecto_id');
            $table->index('modulo_id');
            $table->index('creado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casos_prueba');
    }
};
