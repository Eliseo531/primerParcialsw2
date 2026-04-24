<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabla stub creada por el módulo de Bugs como dependencia temporal.
        // TODO: el responsable de Gestión de Proyectos debe agregar las columnas
        // que necesite mediante una nueva migración (no modificar esta).
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
