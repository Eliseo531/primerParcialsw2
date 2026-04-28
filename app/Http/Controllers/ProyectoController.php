<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Usuario;

class ProyectoController extends Controller
{
    public function index(): View
    {
        $proyectos = Proyecto::with('creador')
            ->latest()
            ->paginate(10);

        return view('proyectos.index', compact('proyectos'));
    }

    public function create(): View
    {
        return view('proyectos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', 'in:activo,finalizado'],
        ]);

        $validated['created_by'] = Auth::id();

        Proyecto::create($validated);

        return redirect()
            ->route('proyectos.index')
            ->with('success', 'Proyecto creado correctamente.');
    }

    public function miembros(Proyecto $proyecto): View
    {
        $proyecto->load('miembros.rol');

        $usuarios = Usuario::with('rol')
            ->where('estado', 'activo')
            ->whereNotIn('id', $proyecto->miembros->pluck('id'))
            ->orderBy('nombre')
            ->get();

        return view('proyectos.miembros', compact('proyecto', 'usuarios'));
    }

    public function asignarMiembro(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $validated = $request->validate([
            'usuario_id' => ['required', 'exists:usuarios,id'],
        ]);

        $proyecto->miembros()->syncWithoutDetaching([
            $validated['usuario_id'] => [
                'fecha_asignacion' => now()->toDateString(),
            ],
        ]);

        return redirect()
            ->route('proyectos.miembros', $proyecto)
            ->with('success', 'Miembro asignado correctamente.');
    }

    public function quitarMiembro(Proyecto $proyecto, Usuario $usuario): RedirectResponse
    {
        $proyecto->miembros()->detach($usuario->id);

        return redirect()
            ->route('proyectos.miembros', $proyecto)
            ->with('success', 'Miembro removido correctamente.');
    }
}
