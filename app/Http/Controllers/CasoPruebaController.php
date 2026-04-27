<?php

namespace App\Http\Controllers;

use App\Models\CasoPrueba;
use App\Models\PasoPrueba;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class CasoPruebaController extends Controller
{
    public function index(Request $request)
    {
        $query = CasoPrueba::with(['creador', 'proyecto'])
            ->withCount(['pasos', 'ejecuciones'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('proyecto_id')) {
            $query->where('proyecto_id', $request->proyecto_id);
        }

        $casos = $query->paginate(15)->withQueryString();
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('casos_prueba.index', compact('casos', 'proyectos'));
    }

    public function create()
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('casos_prueba.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $data = $request->validate([
            'nombre'             => 'required|string|max:200',
            'descripcion'        => 'nullable|string',
            'condiciones'        => 'nullable|string',
            'resultado_esperado' => 'required|string',
            'proyecto_id'        => 'required|integer',
            'pasos'              => 'nullable|array',
            'pasos.*.descripcion'=> 'required_with:pasos|string',
        ]);

        $caso = CasoPrueba::create([
            'nombre'             => $data['nombre'],
            'descripcion'        => $data['descripcion'] ?? null,
            'condiciones'        => $data['condiciones'] ?? null,
            'resultado_esperado' => $data['resultado_esperado'],
            'proyecto_id'        => $data['proyecto_id'],
            'creado_por'         => auth()->id(),
        ]);

        foreach (($request->input('pasos', [])) as $index => $paso) {
            if (!empty(trim($paso['descripcion'] ?? ''))) {
                PasoPrueba::create([
                    'caso_prueba_id' => $caso->id,
                    'orden'          => $index + 1,
                    'descripcion'    => $paso['descripcion'],
                ]);
            }
        }

        return redirect()->route('pruebas.show', $caso)->with('success', 'Caso de prueba creado correctamente.');
    }

    public function show(CasoPrueba $caso)
    {
        $caso->load([
            'pasos',
            'creador',
            'proyecto',
            'ejecuciones.ejecutor',
            'ejecuciones.bugs',
        ]);

        return view('casos_prueba.show', compact('caso'));
    }

    public function edit(CasoPrueba $caso)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $caso->load('pasos');
        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('casos_prueba.edit', compact('caso', 'proyectos'));
    }

    public function update(Request $request, CasoPrueba $caso)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $data = $request->validate([
            'nombre'             => 'required|string|max:200',
            'descripcion'        => 'nullable|string',
            'condiciones'        => 'nullable|string',
            'resultado_esperado' => 'required|string',
            'proyecto_id'        => 'required|integer',
            'pasos'              => 'nullable|array',
            'pasos.*.descripcion'=> 'required_with:pasos|string',
        ]);

        $caso->update([
            'nombre'             => $data['nombre'],
            'descripcion'        => $data['descripcion'] ?? null,
            'condiciones'        => $data['condiciones'] ?? null,
            'resultado_esperado' => $data['resultado_esperado'],
            'proyecto_id'        => $data['proyecto_id'],
        ]);

        $caso->pasos()->delete();

        foreach (($request->input('pasos', [])) as $index => $paso) {
            if (!empty(trim($paso['descripcion'] ?? ''))) {
                PasoPrueba::create([
                    'caso_prueba_id' => $caso->id,
                    'orden'          => $index + 1,
                    'descripcion'    => $paso['descripcion'],
                ]);
            }
        }

        return redirect()->route('pruebas.show', $caso)->with('success', 'Caso de prueba actualizado correctamente.');
    }

    private function autorizarRoles(array $roles): void
    {
        if (!in_array(auth()->user()->rol->nombre, $roles)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }
}
