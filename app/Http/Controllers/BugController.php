<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\ModuloProyecto;
use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\HistorialBug;
use App\Models\Tarea;
use Carbon\Carbon;


class BugController extends Controller
{
    public function index(): View
    {
        $bugs = Bug::with(['proyecto', 'modulo', 'tarea', 'reportero', 'asignado'])
            ->latest()
            ->paginate(10);

        return view('bugs.index', compact('bugs'));
    }

    public function create(): View
    {
        $proyectos = Proyecto::orderBy('nombre')->get();

        $modulos = ModuloProyecto::with('proyecto')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $desarrolladores = Usuario::with('rol')
            ->where('estado', 'activo')
            ->whereHas('rol', function ($query) {
                $query->where('nombre', 'Desarrollador');
            })
            ->orderBy('nombre')
            ->get();

        $tareas = Tarea::with(['proyecto', 'modulo'])
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->orderBy('titulo')
            ->get();

        return view('bugs.create', compact('proyectos', 'modulos', 'desarrolladores', 'tareas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'modulo_id' => ['nullable', 'exists:modulos_proyecto,id'],
            'titulo' => ['required', 'string', 'max:150'],
            'descripcion' => ['required', 'string'],
            'pasos_reproducir' => ['nullable', 'string'],
            'resultado_esperado' => ['nullable', 'string'],
            'resultado_actual' => ['nullable', 'string'],
            'severidad' => ['required', 'in:baja,media,alta,critica'],
            'asignado_a' => ['nullable', 'exists:usuarios,id'],
            'tarea_id' => ['nullable', 'exists:tareas,id'],
        ]);

        $validated['estado'] = 'abierto';
        $validated['reportado_por'] = Auth::id();

        if (!empty($validated['tarea_id'])) {
            $tarea = Tarea::find($validated['tarea_id']);

            if ($tarea && $tarea->responsable_id) {
                $validated['asignado_a'] = $tarea->responsable_id;
            }
        }

        Bug::create($validated);

        return redirect()
            ->route('bugs.index')
            ->with('success', 'Bug registrado correctamente.');
    }

    public function show(Bug $bug): View
    {
        $bug->load([
            'proyecto',
            'modulo',
            'tarea',
            'reportero',
            'asignado',
            'historial.usuario',
        ]);;

        return view('bugs.show', compact('bug'));
    }

    public function cambiarEstado(Request $request, Bug $bug): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:abierto,en_proceso,cerrado'],
            'comentario' => ['nullable', 'string'],
        ]);

        $estadoAnterior = $bug->estado;
        $estadoNuevo = $validated['estado'];

        if ($estadoAnterior === $estadoNuevo) {
            return redirect()
                ->route('bugs.show', $bug)
                ->with('success', 'El bug ya se encuentra en ese estado.');
        }

        $dataBug = [
            'estado' => $estadoNuevo,
        ];

        if ($estadoNuevo === 'cerrado') {
            $fechaResolucion = now();

            $dataBug['fecha_resolucion'] = $fechaResolucion;

            if ($bug->fecha_reporte) {
                $dataBug['tiempo_resolucion_horas'] = round(
                    $bug->fecha_reporte->diffInMinutes($fechaResolucion) / 60,
                    2
                );
            }
        }

        if ($estadoNuevo !== 'cerrado') {
            $dataBug['fecha_resolucion'] = null;
            $dataBug['tiempo_resolucion_horas'] = null;
        }

        $bug->update($dataBug);

        HistorialBug::create([
            'bug_id' => $bug->id,
            'usuario_id' => Auth::id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'comentario' => $validated['comentario'] ?? null,
            'fecha_cambio' => now(),
        ]);

        return redirect()
            ->route('bugs.show', $bug)
            ->with('success', 'Estado del bug actualizado correctamente.');
    }
}
