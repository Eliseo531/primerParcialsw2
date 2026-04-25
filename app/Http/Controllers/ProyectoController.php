<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    // listar
    public function index()
    {
        $proyectos = Proyecto::all();
        $usuarios = Usuario::all();

        return view('proyectos.index', compact('proyectos', 'usuarios'));
    }

    // crear
    public function create()
    {
        return view('proyectos.create');
    }

    //guardar
    public function store(Request $request)
    {
        Proyecto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => 'activo',
            'created_by' => 1 
        ]);

        return redirect()->route('proyectos.index');
    }

    //editar
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $usuarios = Usuario::all();

        return view('proyectos.edit', compact('proyecto', 'usuarios'));
       
    }

    // actualizar
    public function update(Request $request, $id)
{
    $proyecto = Proyecto::findOrFail($id);

    $proyecto->update([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'estado' => $request->estado ?? $proyecto->estado, // 🔥 evita NULL
    ]);

    $proyecto->miembros()->sync($request->usuarios ?? []);
    return redirect()->route('proyectos.index');
}

    // eliminar
    public function destroy($id)
    {
        Proyecto::destroy($id);

        return redirect()->route('proyectos.index');
    }

    // asignar miembros 
    public function asignarMiembros(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $proyecto->miembros()->sync($request->usuarios ?? []);

        return redirect()->route('proyectos.index');
    }
}