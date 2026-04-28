<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recomendaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();

            $table->foreignId('modulo_id')
                ->nullable()
                ->constrained('modulos_proyecto')
                ->nullOnDelete();

            $table->string('tipo', 50)->nullable();
            $table->text('descripcion');
            $table->string('prioridad', 20)->default('media');
            $table->boolean('generado_por_sistema')->default(true);
            $table->string('estado', 20)->default('pendiente');
            $table->timestamp('fecha_generacion')->useCurrent();

            $table->timestamps();

            $table->index('proyecto_id');
            $table->index('modulo_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recomendaciones');
    }
};
