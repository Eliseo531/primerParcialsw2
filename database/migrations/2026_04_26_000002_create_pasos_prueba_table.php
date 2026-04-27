<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pasos_prueba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caso_prueba_id')->constrained('casos_prueba')->cascadeOnDelete();
            $table->unsignedInteger('orden');
            $table->text('descripcion');
            $table->timestamps();

            $table->index('caso_prueba_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasos_prueba');
    }
};
