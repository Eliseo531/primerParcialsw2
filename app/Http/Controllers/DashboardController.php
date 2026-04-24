<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_bugs' => 120,
            'bugs_abiertos' => 45,
            'bugs_cerrados' => 75,
            'tasa_pruebas' => 83,
        ];

        $recentBugs = [
            [
                'id' => 1,
                'titulo' => 'Error al iniciar sesión',
                'modulo' => 'Usuarios',
                'severidad' => 'Alta',
                'estado' => 'Abierto',
                'asignado' => 'Carlos',
            ],
            [
                'id' => 2,
                'titulo' => 'Falla en cálculo de métricas',
                'modulo' => 'Métricas',
                'severidad' => 'Media',
                'estado' => 'En proceso',
                'asignado' => 'Ana',
            ],
            [
                'id' => 3,
                'titulo' => 'No carga gráfico principal',
                'modulo' => 'Dashboard',
                'severidad' => 'Baja',
                'estado' => 'Cerrado',
                'asignado' => 'Luis',
            ],
        ];

        $activities = [
            'Se registró el bug #45 en el módulo Usuarios',
            'Se ejecutó una prueba con resultado FAIL',
            'El bug #32 fue cerrado correctamente',
            'Se generó una recomendación automática de mejora',
        ];

        $usuario = Auth::user();

        return view('dashboard.index', compact(
            'stats',
            'recentBugs',
            'activities',
            'usuario'
        ));
    }
}
