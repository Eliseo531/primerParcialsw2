<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones_calidad', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();

            $table->foreignId('evaluado_por')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->decimal('usabilidad', 5, 2);
            $table->decimal('rendimiento', 5, 2);
            $table->decimal('seguridad', 5, 2);
            $table->decimal('indice_calidad_global', 5, 2)->nullable();

            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_evaluacion')->useCurrent();

            $table->timestamps();

            $table->index('proyecto_id');
            $table->index('evaluado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_calidad');
    }
};
