@extends('layouts.dashboard')

@section('content')
    @php
        $rol = auth()->user()->rol->nombre ?? null;
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tareas</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Gestión de tareas del proyecto.
                </p>
            </div>

            @if ($rol === 'Administrador')
                <a href="{{ route('tareas.create') }}"
                    class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    + Nueva tarea
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">ID</th>
                            <th class="px-6 py-4 font-semibold">Título</th>
                            <th class="px-6 py-4 font-semibold">Proyecto</th>
                            <th class="px-6 py-4 font-semibold">Módulo</th>
                            <th class="px-6 py-4 font-semibold">Responsable</th>
                            <th class="px-6 py-4 font-semibold">Prioridad</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tareas as $tarea)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">{{ $tarea->id }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $tarea->titulo }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $tarea->proyecto->nombre ?? 'Sin proyecto' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $tarea->modulo->nombre ?? 'Sin módulo' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($tarea->responsable)
                                        {{ $tarea->responsable->nombre }} {{ $tarea->responsable->apellido }}
                                    @else
                                        Sin asignar
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    {{ ucfirst($tarea->prioridad) }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $estadoColor = match ($tarea->estado) {
                                            'completado' => 'bg-green-100 text-green-700',
                                            'en_progreso' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $estadoColor }}">
                                        {{ str_replace('_', ' ', ucfirst($tarea->estado)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('tareas.show', $tarea) }}"
                                            class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                            Ver
                                        </a>

                                        @if ($rol === 'Administrador')
                                            <a href="{{ route('tareas.edit', $tarea) }}"
                                                class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-700 transition">
                                                Editar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    No hay tareas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $tareas->links() }}
            </div>
        </div>
    </div>
@endsection
