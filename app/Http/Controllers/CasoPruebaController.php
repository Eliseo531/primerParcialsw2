<?php

namespace App\Http\Controllers;

use App\Models\CasoPrueba;
use App\Models\ModuloProyecto;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CasoPruebaController extends Controller
{
    public function index(): View
    {
        $casos = CasoPrueba::with(['proyecto', 'modulo', 'creador'])
            ->latest()
            ->paginate(10);

        return view('casos_prueba.index', compact('casos'));
    }

    public function create(): View
    {
        $proyectos = Proyecto::orderBy('nombre')->get();

        $modulos = ModuloProyecto::with('proyecto')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view('casos_prueba.create', compact('proyectos', 'modulos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'modulo_id' => ['nullable', 'exists:modulos_proyecto,id'],
            'titulo' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'precondiciones' => ['nullable', 'string'],
            'pasos' => ['required', 'string'],
            'resultado_esperado' => ['required', 'string'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $validated['creado_por'] = Auth::id();

        CasoPrueba::create($validated);

        return redirect()
            ->route('casos-prueba.index')
            ->with('success', 'Caso de prueba creado correctamente.');
    }
}
