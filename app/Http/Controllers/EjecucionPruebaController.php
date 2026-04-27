<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\CasoPrueba;
use App\Models\EjecucionPrueba;
use Illuminate\Http\Request;

class EjecucionPruebaController extends Controller
{
    public function index()
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $ejecuciones = EjecucionPrueba::with(['caso', 'ejecutor', 'bugs'])
            ->orderBy('fecha_ejecucion', 'desc')
            ->paginate(15);

        return view('ejecuciones_prueba.index', compact('ejecuciones'));
    }

    public function create(CasoPrueba $caso)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $caso->load('pasos');

        $bugsAbiertos = Bug::where('estado', 'abierto')
            ->orderBy('id', 'desc')
            ->get();

        return view('ejecuciones_prueba.create', compact('caso', 'bugsAbiertos'));
    }

    public function store(Request $request, CasoPrueba $caso)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $data = $request->validate([
            'resultado'     => 'required|in:OK,FAIL',
            'observaciones' => 'nullable|string',
            'bugs'          => 'nullable|array',
            'bugs.*'        => 'integer|exists:bugs,id',
        ]);

        $ejecucion = EjecucionPrueba::create([
            'caso_prueba_id' => $caso->id,
            'ejecutado_por'  => auth()->id(),
            'resultado'      => $data['resultado'],
            'observaciones'  => $data['observaciones'] ?? null,
            'fecha_ejecucion'=> now(),
        ]);

        if ($data['resultado'] === 'FAIL' && !empty($data['bugs'])) {
            $ejecucion->bugs()->sync($data['bugs']);
        }

        return redirect()->route('pruebas.show', $caso)->with('success', 'Ejecución registrada correctamente.');
    }

    private function autorizarRoles(array $roles): void
    {
        if (!in_array(auth()->user()->rol->nombre, $roles)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }
}
