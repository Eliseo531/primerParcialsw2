<?php

namespace App\Http\Controllers;

use App\Models\CasoPrueba;
use App\Models\EjecucionPrueba;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Bug;


class EjecucionPruebaController extends Controller
{
    public function index(CasoPrueba $casoPrueba): View
    {
        $casoPrueba->load(['proyecto', 'modulo', 'creador']);

        $ejecuciones = $casoPrueba->ejecuciones()
            ->with('ejecutor')
            ->latest('fecha_ejecucion')
            ->paginate(10);

        return view('ejecuciones_prueba.index', compact('casoPrueba', 'ejecuciones'));
    }

    public function create(CasoPrueba $casoPrueba): View
    {
        $casoPrueba->load(['proyecto', 'modulo']);

        return view('ejecuciones_prueba.create', compact('casoPrueba'));
    }

    public function store(Request $request, CasoPrueba $casoPrueba): RedirectResponse
    {
        $validated = $request->validate([
            'resultado' => ['required', 'in:OK,FAIL'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $validated['caso_prueba_id'] = $casoPrueba->id;
        $validated['ejecutado_por'] = Auth::id();
        $validated['fecha_ejecucion'] = now();

        EjecucionPrueba::create($validated);

        return redirect()
            ->route('casos-prueba.ejecuciones.index', $casoPrueba)
            ->with('success', 'Ejecución registrada correctamente.');
    }

    public function relacionarBug(EjecucionPrueba $ejecucion): View
    {
        $ejecucion->load(['casoPrueba.proyecto', 'casoPrueba.modulo', 'bugs']);

        $bugs = Bug::with(['proyecto', 'modulo'])
            ->where('proyecto_id', $ejecucion->casoPrueba->proyecto_id)
            ->whereNotIn('id', $ejecucion->bugs->pluck('id'))
            ->latest()
            ->get();

        return view('ejecuciones_prueba.relacionar_bug', compact('ejecucion', 'bugs'));
    }

    public function guardarRelacionBug(Request $request, EjecucionPrueba $ejecucion): RedirectResponse
    {
        $validated = $request->validate([
            'bug_id' => ['required', 'exists:bugs,id'],
        ]);

        if ($ejecucion->resultado !== 'FAIL') {
            return redirect()
                ->route('casos-prueba.ejecuciones.index', $ejecucion->caso_prueba_id)
                ->with('success', 'Solo las ejecuciones FAIL pueden relacionarse con bugs.');
        }

        $ejecucion->bugs()->syncWithoutDetaching([$validated['bug_id']]);

        return redirect()
            ->route('casos-prueba.ejecuciones.index', $ejecucion->caso_prueba_id)
            ->with('success', 'Bug relacionado correctamente con la ejecución.');
    }
}
