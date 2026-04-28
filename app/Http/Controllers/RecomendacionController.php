<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\EjecucionPrueba;
use App\Models\EvaluacionCalidad;
use App\Models\Proyecto;
use App\Models\Recomendacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecomendacionController extends Controller
{
    public function index(): View
    {
        $proyectos = Proyecto::orderBy('nombre')->get();

        $recomendaciones = Recomendacion::with(['proyecto', 'modulo'])
            ->latest('fecha_generacion')
            ->paginate(10);

        return view('recomendaciones.index', compact('proyectos', 'recomendaciones'));
    }

    public function generar(Proyecto $proyecto): RedirectResponse
    {
        $recomendacionesGeneradas = 0;

        $recomendacionesGeneradas += $this->generarPorBugsCriticos($proyecto);
        $recomendacionesGeneradas += $this->generarPorModulosCriticos($proyecto);
        $recomendacionesGeneradas += $this->generarPorPruebasFallidas($proyecto);
        $recomendacionesGeneradas += $this->generarPorCalidadBaja($proyecto);
        $recomendacionesGeneradas += $this->generarPorTiempoResolucion($proyecto);
        $recomendacionesGeneradas += $this->generarPorBugsAbiertos($proyecto);

        $mensaje = $recomendacionesGeneradas > 0
            ? "Se generaron {$recomendacionesGeneradas} recomendación(es)."
            : "No se encontraron condiciones críticas para generar recomendaciones.";

        return redirect()
            ->route('recomendaciones.index')
            ->with('success', $mensaje);
    }

    private function generarPorBugsCriticos(Proyecto $proyecto): int
    {
        $bugsCriticos = Bug::where('proyecto_id', $proyecto->id)
            ->where('severidad', 'critica')
            ->whereIn('estado', ['abierto', 'en_proceso'])
            ->count();

        if ($bugsCriticos <= 0) {
            return 0;
        }

        Recomendacion::create([
            'proyecto_id' => $proyecto->id,
            'tipo' => 'Bugs críticos',
            'descripcion' => "Se detectaron {$bugsCriticos} bug(s) críticos activos. Se recomienda priorizar su corrección antes de continuar con nuevas funcionalidades.",
            'prioridad' => $bugsCriticos >= 3 ? 'alta' : 'media',
            'generado_por_sistema' => true,
            'estado' => 'pendiente',
            'fecha_generacion' => now(),
        ]);

        return 1;
    }

    private function generarPorModulosCriticos(Proyecto $proyecto): int
    {
        $generadas = 0;

        $modulos = $proyecto->modulos()
            ->withCount('bugs')
            ->get();

        foreach ($modulos as $modulo) {
            if ($modulo->bugs_count >= 2) {
                Recomendacion::create([
                    'proyecto_id' => $proyecto->id,
                    'modulo_id' => $modulo->id,
                    'tipo' => 'Módulo crítico',
                    'descripcion' => "El módulo {$modulo->nombre} acumula {$modulo->bugs_count} bug(s). Se recomienda revisar su diseño, validaciones y pruebas asociadas.",
                    'prioridad' => $modulo->bugs_count >= 4 ? 'alta' : 'media',
                    'generado_por_sistema' => true,
                    'estado' => 'pendiente',
                    'fecha_generacion' => now(),
                ]);

                $generadas++;
            }
        }

        return $generadas;
    }

    private function generarPorPruebasFallidas(Proyecto $proyecto): int
    {
        $casosIds = $proyecto->casosPrueba()->pluck('id');

        $totalPruebas = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)->count();

        $pruebasFail = EjecucionPrueba::whereIn('caso_prueba_id', $casosIds)
            ->where('resultado', 'FAIL')
            ->count();

        if ($totalPruebas <= 0) {
            return 0;
        }

        $tasaFallos = round(($pruebasFail / $totalPruebas) * 100, 2);

        if ($tasaFallos < 30) {
            return 0;
        }

        Recomendacion::create([
            'proyecto_id' => $proyecto->id,
            'tipo' => 'Pruebas fallidas',
            'descripcion' => "La tasa de fallos en pruebas es de {$tasaFallos}%. Se recomienda revisar los casos FAIL, relacionarlos con bugs y repetir las pruebas después de la corrección.",
            'prioridad' => $tasaFallos >= 50 ? 'alta' : 'media',
            'generado_por_sistema' => true,
            'estado' => 'pendiente',
            'fecha_generacion' => now(),
        ]);

        return 1;
    }

    private function generarPorCalidadBaja(Proyecto $proyecto): int
    {
        $ultimaEvaluacion = EvaluacionCalidad::where('proyecto_id', $proyecto->id)
            ->latest('fecha_evaluacion')
            ->first();

        if (!$ultimaEvaluacion || $ultimaEvaluacion->indice_calidad_global >= 70) {
            return 0;
        }

        Recomendacion::create([
            'proyecto_id' => $proyecto->id,
            'tipo' => 'Calidad baja',
            'descripcion' => "El índice de calidad global es {$ultimaEvaluacion->indice_calidad_global}%. Se recomienda aplicar acciones correctivas en usabilidad, rendimiento y seguridad.",
            'prioridad' => $ultimaEvaluacion->indice_calidad_global < 50 ? 'alta' : 'media',
            'generado_por_sistema' => true,
            'estado' => 'pendiente',
            'fecha_generacion' => now(),
        ]);

        return 1;
    }

    private function generarPorTiempoResolucion(Proyecto $proyecto): int
    {
        $tiempoPromedio = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'cerrado')
            ->whereNotNull('tiempo_resolucion_horas')
            ->avg('tiempo_resolucion_horas');

        if (!$tiempoPromedio || $tiempoPromedio <= 8) {
            return 0;
        }

        $tiempoPromedio = round($tiempoPromedio, 2);

        Recomendacion::create([
            'proyecto_id' => $proyecto->id,
            'tipo' => 'Tiempo de resolución',
            'descripcion' => "El tiempo promedio de resolución es de {$tiempoPromedio} horas. Se recomienda mejorar la asignación de responsables y priorizar bugs de mayor severidad.",
            'prioridad' => $tiempoPromedio >= 24 ? 'alta' : 'media',
            'generado_por_sistema' => true,
            'estado' => 'pendiente',
            'fecha_generacion' => now(),
        ]);

        return 1;
    }

    private function generarPorBugsAbiertos(Proyecto $proyecto): int
    {
        $bugsAbiertos = Bug::where('proyecto_id', $proyecto->id)
            ->where('estado', 'abierto')
            ->count();

        if ($bugsAbiertos < 3) {
            return 0;
        }

        Recomendacion::create([
            'proyecto_id' => $proyecto->id,
            'tipo' => 'Bugs pendientes',
            'descripcion' => "Existen {$bugsAbiertos} bugs abiertos. Se recomienda planificar una jornada de corrección y seguimiento.",
            'prioridad' => $bugsAbiertos >= 6 ? 'alta' : 'media',
            'generado_por_sistema' => true,
            'estado' => 'pendiente',
            'fecha_generacion' => now(),
        ]);

        return 1;
    }

    public function actualizarEstado(Request $request, Recomendacion $recomendacion): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:pendiente,aplicada,descartada'],
        ]);

        $recomendacion->update([
            'estado' => $validated['estado'],
        ]);

        return redirect()
            ->route('recomendaciones.index')
            ->with('success', 'Estado de recomendación actualizado correctamente.');
    }
}
