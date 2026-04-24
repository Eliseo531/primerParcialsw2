<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\HistorialBug;
use App\Models\Usuario;
use Illuminate\Http\Request;

class BugController extends Controller
{
    // Todos los usuarios autenticados pueden ver el listado
    public function index(Request $request)
    {
        $query = Bug::with(['reportador', 'asignado'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('severidad')) {
            $query->where('severidad', $request->severidad);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $bugs = $query->paginate(15)->withQueryString();

        return view('bugs.index', compact('bugs'));
    }

    // Solo Tester puede registrar bugs
    public function create()
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $desarrolladores = Usuario::whereHas('rol', fn($q) => $q->where('nombre', 'Desarrollador'))
            ->where('estado', 'activo')
            ->get();

        return view('bugs.create', compact('desarrolladores'));
    }

    public function store(Request $request)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $data = $request->validate([
            'proyecto_id'       => 'required|integer',
            'titulo'            => 'required|string|max:150',
            'descripcion'       => 'required|string',
            'pasos_reproducir'  => 'nullable|string',
            'resultado_esperado'=> 'nullable|string',
            'resultado_actual'  => 'nullable|string',
            'severidad'         => 'required|in:baja,media,alta',
            'asignado_a'        => 'nullable|exists:usuarios,id',
        ]);

        $data['reportado_por'] = auth()->id();
        $data['estado']        = 'abierto';

        $bug = Bug::create($data);

        HistorialBug::create([
            'bug_id'          => $bug->id,
            'usuario_id'      => auth()->id(),
            'estado_anterior' => null,
            'estado_nuevo'    => 'abierto',
            'comentario'      => 'Bug registrado en el sistema.',
        ]);

        return redirect()->route('bugs.index')->with('success', 'Bug registrado correctamente.');
    }

    // Tester y Desarrollador pueden ver el detalle e historial
    public function show(Bug $bug)
    {
        $this->autorizarRoles(['Tester', 'Desarrollador', 'Administrador']);

        $bug->load(['reportador', 'asignado', 'historial.usuario']);

        $desarrolladores = Usuario::whereHas('rol', fn($q) => $q->where('nombre', 'Desarrollador'))
            ->where('estado', 'activo')
            ->get();

        return view('bugs.show', compact('bug', 'desarrolladores'));
    }

    // Solo Tester edita los datos del bug
    public function edit(Bug $bug)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $desarrolladores = Usuario::whereHas('rol', fn($q) => $q->where('nombre', 'Desarrollador'))
            ->where('estado', 'activo')
            ->get();

        return view('bugs.edit', compact('bug', 'desarrolladores'));
    }

    public function update(Request $request, Bug $bug)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $data = $request->validate([
            'titulo'            => 'required|string|max:150',
            'descripcion'       => 'required|string',
            'pasos_reproducir'  => 'nullable|string',
            'resultado_esperado'=> 'nullable|string',
            'resultado_actual'  => 'nullable|string',
            'severidad'         => 'required|in:baja,media,alta',
            'asignado_a'        => 'nullable|exists:usuarios,id',
        ]);

        $bug->update($data);

        return redirect()->route('bugs.show', $bug)->with('success', 'Bug actualizado correctamente.');
    }

    // Tester y Administrador asignan el bug a un desarrollador
    public function asignar(Request $request, Bug $bug)
    {
        $this->autorizarRoles(['Tester', 'Administrador']);

        $request->validate([
            'asignado_a' => 'required|exists:usuarios,id',
        ]);

        $estadoAnterior     = $bug->estado;
        $bug->asignado_a    = $request->asignado_a;
        $bug->save();

        $desarrollador = Usuario::find($request->asignado_a);

        HistorialBug::create([
            'bug_id'          => $bug->id,
            'usuario_id'      => auth()->id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $estadoAnterior,
            'comentario'      => 'Bug asignado a ' . $desarrollador->nombre_completo . '.',
        ]);

        return redirect()->route('bugs.show', $bug)->with('success', 'Bug asignado correctamente.');
    }

    // Solo el Desarrollador cambia el estado (en proceso / cerrado)
    public function cambiarEstado(Request $request, Bug $bug)
    {
        $this->autorizarRoles(['Desarrollador']);

        if ($bug->asignado_a !== auth()->id()) {
            abort(403, 'Solo el desarrollador asignado puede cambiar el estado.');
        }

        $request->validate([
            'estado'     => 'required|in:en proceso,cerrado',
            'comentario' => 'nullable|string|max:500',
        ]);

        $estadoAnterior = $bug->estado;
        $bug->estado    = $request->estado;

        if ($request->estado === 'cerrado') {
            $bug->fecha_resolucion        = now();
            $bug->tiempo_resolucion_horas = $bug->fecha_reporte->diffInHours(now());
        }

        $bug->save();

        HistorialBug::create([
            'bug_id'          => $bug->id,
            'usuario_id'      => auth()->id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $request->estado,
            'comentario'      => $request->comentario,
        ]);

        return redirect()->route('bugs.show', $bug)->with('success', 'Estado actualizado correctamente.');
    }

    private function autorizarRoles(array $roles): void
    {
        if (!in_array(auth()->user()->rol->nombre, $roles)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }
}
