<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();

        if (!$rolAdmin) {
            return;
        }

        Usuario::updateOrCreate(
            ['email' => 'admin@calidad.com'],
            [
                'nombre' => 'Admin',
                'apellido' => 'Principal',
                'password' => Hash::make('12345678'),
                'rol_id' => $rolAdmin->id,
                'estado' => 'activo',
            ]
        );
    }
}
