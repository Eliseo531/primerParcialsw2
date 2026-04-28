<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\EjecucionPrueba;
use App\Models\EvaluacionCalidad;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;



class EvaluacionCalidadController extends Controller
{
    public function index(): View
    {
        $evaluaciones = EvaluacionCalidad::with(['proyecto', 'evaluador'])
            ->latest('fecha_evaluacion')
            ->paginate(10);

        return view('evaluaciones_calidad.index', compact('evaluaciones'));
    }

    public function create(): View
    {
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('evaluaciones_calidad.create', compact('proyectos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'usabilidad' => ['required', 'numeric', 'min:0', 'max:100'],
            'rendimiento' => ['required', 'numeric', 'min:0', 'max:100'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $proyecto = Proyecto::findOrFail($validated['proyecto_id']);

        $bugsTotales = Bug::where('proyecto_id', $proyecto->id)->count();

        $bugsCerrados = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'cerrado')
            ->count();

        $bugsCriticosActivos = Bug::where('proyecto_id', $proyecto->id)
            ->where('severidad', 'critica')
            ->whereIn('estado', ['abierto', 'en_proceso'])
            ->count();

        $casosIds = $proyecto->casosPrueba()->pluck('id');

        $totalPruebas = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)->count();

        $pruebasOk = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)
            ->where('resultado', 'OK')
            ->count();

        $calidadPruebas = $totalPruebas > 0
            ? round(($pruebasOk / $totalPruebas) * 100, 2)
            : 100;

        $calidadBugs = $bugsTotales > 0
            ? round(($bugsCerrados / $bugsTotales) * 100, 2)
            : 100;

        $seguridadCalculada = max(0, 100 - ($bugsCriticosActivos * 20));

        $indiceGlobal = round(
            ($validated['usabilidad'] * 0.30) +
                ($validated['rendimiento'] * 0.20) +
                ($seguridadCalculada * 0.20) +
                ($calidadPruebas * 0.15) +
                ($calidadBugs * 0.15),
            2
        );

        $observacionesAutomaticas = $this->generarObservacionAutomatica(
            $indiceGlobal,
            $seguridadCalculada,
            $calidadPruebas,
            $calidadBugs,
            $bugsCriticosActivos
        );

        $observaciones = trim(
            ($validated['observaciones'] ?? '') .
                "\n\nObservación automática:\n" .
                $observacionesAutomaticas
        );

        EvaluacionCalidad::create([
            'proyecto_id' => $validated['proyecto_id'],
            'evaluado_por' => Auth::id(),
            'usabilidad' => $validated['usabilidad'],
            'rendimiento' => $validated['rendimiento'],
            'seguridad' => $seguridadCalculada,
            'indice_calidad_global' => $indiceGlobal,
            'observaciones' => $observaciones,
            'fecha_evaluacion' => now(),
        ]);

        return redirect()
            ->route('evaluaciones-calidad.index')
            ->with('success', 'Evaluación de calidad registrada correctamente con cálculo automático.');
    }

    public function calcularPreview(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'usabilidad' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rendimiento' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $proyecto = Proyecto::findOrFail($validated['proyecto_id']);

        $usabilidad = (float) ($validated['usabilidad'] ?? 0);
        $rendimiento = (float) ($validated['rendimiento'] ?? 0);

        $bugsTotales = Bug::where('proyecto_id', $proyecto->id)->count();

        $bugsCerrados = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'cerrado')
            ->count();

        $bugsCriticosActivos = Bug::where('proyecto_id', $proyecto->id)
            ->where('severidad', 'critica')
            ->whereIn('estado', ['abierto', 'en_proceso'])
            ->count();

        $casosIds = $proyecto->casosPrueba()->pluck('id');

        $totalPruebas = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)->count();

        $pruebasOk = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)
            ->where('resultado', 'OK')
            ->count();

        $calidadPruebas = $totalPruebas > 0
            ? round(($pruebasOk / $totalPruebas) * 100, 2)
            : 100;

        $calidadBugs = $bugsTotales > 0
            ? round(($bugsCerrados / $bugsTotales) * 100, 2)
            : 100;

        $seguridad = max(0, 100 - ($bugsCriticosActivos * 20));

        $indiceGlobal = round(
            ($usabilidad * 0.30) +
                ($rendimiento * 0.20) +
                ($seguridad * 0.20) +
                ($calidadPruebas * 0.15) +
                ($calidadBugs * 0.15),
            2
        );

        return response()->json([
            'seguridad' => $seguridad,
            'calidad_pruebas' => $calidadPruebas,
            'calidad_bugs' => $calidadBugs,
            'indice_global' => $indiceGlobal,
            'bugs_totales' => $bugsTotales,
            'bugs_cerrados' => $bugsCerrados,
            'bugs_criticos_activos' => $bugsCriticosActivos,
            'total_pruebas' => $totalPruebas,
            'pruebas_ok' => $pruebasOk,
        ]);
    }

    public function show(EvaluacionCalidad $evaluacion): View
    {
        $evaluacion->load(['proyecto', 'evaluador']);

        return view('evaluaciones_calidad.show', compact('evaluacion'));
    }

    private function generarObservacionAutomatica(
        float $indiceGlobal,
        float $seguridad,
        float $calidadPruebas,
        float $calidadBugs,
        int $bugsCriticosActivos
    ): string {
        $nivel = match (true) {
            $indiceGlobal >= 85 => 'excelente',
            $indiceGlobal >= 70 => 'buena',
            $indiceGlobal >= 50 => 'media',
            default => 'crítica',
        };

        $mensaje = "El proyecto presenta una calidad {$nivel} con un índice global de {$indiceGlobal}%.";

        if ($bugsCriticosActivos > 0) {
            $mensaje .= " Se detectaron {$bugsCriticosActivos} bug(s) críticos activos, lo cual afecta directamente la seguridad.";
        }

        if ($calidadPruebas < 70) {
            $mensaje .= " La calidad de pruebas es baja ({$calidadPruebas}%), por lo que se recomienda revisar los casos fallidos.";
        }

        if ($calidadBugs < 70) {
            $mensaje .= " La proporción de bugs cerrados es baja ({$calidadBugs}%), se recomienda priorizar la resolución de errores pendientes.";
        }

        if ($seguridad < 70) {
            $mensaje .= " El indicador de seguridad requiere atención debido a la presencia de bugs críticos.";
        }

        return $mensaje;
    }
}
