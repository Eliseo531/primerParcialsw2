<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();

            $table->foreignId('modulo_id')
                ->nullable()
                ->constrained('modulos_proyecto')
                ->nullOnDelete();

            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->text('pasos_reproducir')->nullable();
            $table->text('resultado_esperado')->nullable();
            $table->text('resultado_actual')->nullable();

            $table->string('severidad', 20);
            $table->string('estado', 20)->default('abierto');

            $table->foreignId('reportado_por')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->foreignId('asignado_a')
                ->nullable()
                ->constrained('usuarios')
                ->nullOnDelete();

            $table->timestamp('fecha_reporte')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->decimal('tiempo_resolucion_horas', 10, 2)->nullable();

            $table->timestamps();

            $table->index(['proyecto_id', 'estado']);
            $table->index('modulo_id');
            $table->index('reportado_por');
            $table->index('asignado_a');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
