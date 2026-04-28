<?php

namespace App\Http\Controllers;

use App\Models\ModuloProyecto;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuloProyectoController extends Controller
{
    public function index(Proyecto $proyecto): View
    {
        $modulos = $proyecto->modulos()
            ->latest()
            ->paginate(10);

        return view('modulos.index', compact('proyecto', 'modulos'));
    }

    public function create(Proyecto $proyecto): View
    {
        return view('modulos.create', compact('proyecto'));
    }

    public function store(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $validated['proyecto_id'] = $proyecto->id;

        ModuloProyecto::create($validated);

        return redirect()
            ->route('proyectos.modulos.index', $proyecto)
            ->with('success', 'Módulo creado correctamente.');
    }

    public function edit(Proyecto $proyecto, ModuloProyecto $modulo): View
    {
        return view('modulos.edit', compact('proyecto', 'modulo'));
    }

    public function update(Request $request, Proyecto $proyecto, ModuloProyecto $modulo): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $modulo->update($validated);

        return redirect()
            ->route('proyectos.modulos.index', $proyecto)
            ->with('success', 'Módulo actualizado correctamente.');
    }
}
