<?php

namespace App\Http\Controllers;

use App\Models\ModuloProyecto;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TareaController extends Controller
{
    public function index(): View
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? null;

        $query = Tarea::with(['proyecto', 'modulo', 'responsable', 'creador'])
            ->latest();

        if ($rol === 'Desarrollador') {
            $query->where('responsable_id', $usuario->id);
        }

        $tareas = $query->paginate(10);

        return view('tareas.index', compact('tareas'));
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

        return view('tareas.create', compact('proyectos', 'modulos', 'desarrolladores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'modulo_id' => ['nullable', 'exists:modulos_proyecto,id'],
            'titulo' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'responsable_id' => ['nullable', 'exists:usuarios,id'],
            'prioridad' => ['required', 'in:baja,media,alta'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $validated['estado'] = 'pendiente';
        $validated['created_by'] = Auth::id();

        Tarea::create($validated);

        return redirect()
            ->route('tareas.index')
            ->with('success', 'Tarea creada correctamente.');
    }

    public function show(Tarea $tarea): View
    {
        $tarea->load(['proyecto', 'modulo', 'responsable', 'creador', 'bugs']);

        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea): View
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

        return view('tareas.edit', compact('tarea', 'proyectos', 'modulos', 'desarrolladores'));
    }

    public function update(Request $request, Tarea $tarea): RedirectResponse
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'modulo_id' => ['nullable', 'exists:modulos_proyecto,id'],
            'titulo' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'responsable_id' => ['nullable', 'exists:usuarios,id'],
            'prioridad' => ['required', 'in:baja,media,alta'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $tarea->update($validated);

        return redirect()
            ->route('tareas.index')
            ->with('success', 'Tarea actualizada correctamente.');
    }

    public function cambiarEstado(Request $request, Tarea $tarea): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:pendiente,en_progreso,completado'],
        ]);

        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? null;

        if ($rol === 'Desarrollador' && $tarea->responsable_id !== $usuario->id) {
            abort(403, 'Solo puedes cambiar el estado de tus tareas asignadas.');
        }

        $tarea->update([
            'estado' => $validated['estado'],
        ]);

        return redirect()
            ->route('tareas.show', $tarea)
            ->with('success', 'Estado de la tarea actualizado correctamente.');
    }
}
