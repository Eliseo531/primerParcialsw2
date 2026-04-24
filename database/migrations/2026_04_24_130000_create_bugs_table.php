<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();

            // TODO: cuando Gestión de Proyectos cree la tabla 'proyectos',
            // reemplazar por: $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->unsignedBigInteger('proyecto_id');

            // TODO: cuando Gestión de Proyectos cree la tabla 'modulos_proyecto',
            // reemplazar por: $table->foreignId('modulo_id')->nullable()->constrained('modulos_proyecto')->nullOnDelete();
            $table->unsignedBigInteger('modulo_id')->nullable();

            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->text('pasos_reproducir')->nullable();
            $table->text('resultado_esperado')->nullable();
            $table->text('resultado_actual')->nullable();
            $table->string('severidad', 20); // baja | media | alta
            $table->string('estado', 20)->default('abierto'); // abierto | en proceso | cerrado
            $table->foreignId('reportado_por')->constrained('usuarios')->restrictOnDelete();
            $table->foreignId('asignado_a')->nullable()->constrained('usuarios')->nullOnDelete();
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
