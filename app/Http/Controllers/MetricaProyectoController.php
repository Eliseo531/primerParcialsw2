<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\EjecucionPrueba;
use App\Models\MetricaProyecto;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MetricaProyectoController extends Controller
{
    public function index(): View
    {
        $proyectos = Proyecto::with('metricas')
            ->orderBy('nombre')
            ->get();

        return view('metricas.index', compact('proyectos'));
    }

    public function calcular(Proyecto $proyecto): RedirectResponse
    {
        $totalBugs = Bug::where('proyecto_id', $proyecto->id)->count();

        $bugsAbiertos = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'abierto')
            ->count();

        $bugsEnProceso = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'en_proceso')
            ->count();

        $bugsCerrados = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'cerrado')
            ->count();

        $casosIds = $proyecto->casosPrueba()->pluck('id');

        $totalPruebas = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)->count();

        $pruebasOk = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)
            ->where('resultado', 'OK')
            ->count();

        $pruebasFail = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)
            ->where('resultado', 'FAIL')
            ->count();

        $tasaExito = $totalPruebas > 0
            ? round(($pruebasOk / $totalPruebas) * 100, 2)
            : 0;

        $tiempoPromedio = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'cerrado')
            ->whereNotNull('tiempo_resolucion_horas')
            ->avg('tiempo_resolucion_horas');

        $totalModulos = $proyecto->modulos()->count();

        $densidadDefectos = $totalModulos > 0
            ? round($totalBugs / $totalModulos, 2)
            : 0;

        MetricaProyecto::create([
            'proyecto_id' => $proyecto->id,
            'fecha_calculo' => now(),
            'total_bugs' => $totalBugs,
            'bugs_abiertos' => $bugsAbiertos,
            'bugs_en_proceso' => $bugsEnProceso,
            'bugs_cerrados' => $bugsCerrados,
            'total_pruebas' => $totalPruebas,
            'pruebas_ok' => $pruebasOk,
            'pruebas_fail' => $pruebasFail,
            'tasa_exito_pruebas' => $tasaExito,
            'tiempo_promedio_resolucion' => round($tiempoPromedio ?? 0, 2),
            'densidad_defectos' => $densidadDefectos,
        ]);

        return redirect()
            ->route('metricas.index')
            ->with('success', 'Métricas calculadas correctamente.');
    }
}
