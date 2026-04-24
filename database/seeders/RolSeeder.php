<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::updateOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Control total del sistema']
        );

        Rol::updateOrCreate(
            ['nombre' => 'Desarrollador'],
            ['descripcion' => 'Atiende bugs y trabaja en módulos']
        );

        Rol::updateOrCreate(
            ['nombre' => 'Tester'],
            ['descripcion' => 'Registra bugs y ejecuta pruebas']
        );
    }
}
