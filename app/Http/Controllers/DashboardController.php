<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\EjecucionPrueba;
use App\Models\EvaluacionCalidad;
use App\Models\Proyecto;
use App\Models\Recomendacion;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_proyectos' => Proyecto::count(),
            'total_bugs' => Bug::count(),
            'bugs_abiertos' => Bug::where('estado', 'abierto')->count(),
            'bugs_cerrados' => Bug::where('estado', 'cerrado')->count(),
            'pruebas_ok' => EjecucionPrueba::where('resultado', 'OK')->count(),
            'pruebas_fail' => EjecucionPrueba::where('resultado', 'FAIL')->count(),
            'recomendaciones_pendientes' => Recomendacion::where('estado', 'pendiente')->count(),
        ];

        $totalPruebas = $stats['pruebas_ok'] + $stats['pruebas_fail'];

        $stats['tasa_pruebas'] = $totalPruebas > 0
            ? round(($stats['pruebas_ok'] / $totalPruebas) * 100, 2)
            : 0;

        $bugsPorEstado = [
            'Abiertos' => Bug::where('estado', 'abierto')->count(),
            'En proceso' => Bug::where('estado', 'en_proceso')->count(),
            'Cerrados' => Bug::where('estado', 'cerrado')->count(),
        ];

        $bugsPorSeveridad = [
            'Baja' => Bug::where('severidad', 'baja')->count(),
            'Media' => Bug::where('severidad', 'media')->count(),
            'Alta' => Bug::where('severidad', 'alta')->count(),
            'Crítica' => Bug::where('severidad', 'critica')->count(),
        ];

        $pruebasResultados = [
            'OK' => $stats['pruebas_ok'],
            'FAIL' => $stats['pruebas_fail'],
        ];

        $ultimosBugs = Bug::with(['proyecto', 'modulo', 'reportero', 'asignado'])
            ->latest()
            ->limit(5)
            ->get();

        $ultimasEvaluaciones = EvaluacionCalidad::with('proyecto')
            ->latest('fecha_evaluacion')
            ->limit(5)
            ->get();

        $recomendaciones = Recomendacion::with(['proyecto', 'modulo'])
            ->where('estado', 'pendiente')
            ->latest('fecha_generacion')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'bugsPorEstado',
            'bugsPorSeveridad',
            'pruebasResultados',
            'ultimosBugs',
            'ultimasEvaluaciones',
            'recomendaciones'
        ));
    }
}
