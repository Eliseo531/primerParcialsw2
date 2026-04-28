<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
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

            $table->foreignId('responsable_id')
                ->nullable()
                ->constrained('usuarios')
                ->nullOnDelete();

            $table->string('estado', 20)->default('pendiente');
            $table->string('prioridad', 20)->default('media');

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            $table->foreignId('created_by')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index('proyecto_id');
            $table->index('modulo_id');
            $table->index('responsable_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
