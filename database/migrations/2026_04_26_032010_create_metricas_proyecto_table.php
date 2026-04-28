<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metricas_proyecto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                ->constrained('proyectos')
                ->cascadeOnDelete();

            $table->timestamp('fecha_calculo')->useCurrent();

            $table->unsignedInteger('total_bugs')->default(0);
            $table->unsignedInteger('bugs_abiertos')->default(0);
            $table->unsignedInteger('bugs_en_proceso')->default(0);
            $table->unsignedInteger('bugs_cerrados')->default(0);

            $table->unsignedInteger('total_pruebas')->default(0);
            $table->unsignedInteger('pruebas_ok')->default(0);
            $table->unsignedInteger('pruebas_fail')->default(0);

            $table->decimal('tasa_exito_pruebas', 5, 2)->default(0);
            $table->decimal('tiempo_promedio_resolucion', 10, 2)->default(0);
            $table->decimal('densidad_defectos', 10, 2)->default(0);

            $table->timestamps();

            $table->index('proyecto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metricas_proyecto');
    }
};
