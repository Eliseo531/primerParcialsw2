@extends('layouts.dashboard')

@section('content')
    @php
        $rol = auth()->user()->rol->nombre ?? null;
        $puedeCambiarEstado =
            $rol === 'Administrador' || ($rol === 'Desarrollador' && $tarea->responsable_id === auth()->id());
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detalle de tarea {{ $tarea->id }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $tarea->titulo }}</p>
            </div>

            <a href="{{ route('tareas.index') }}"
                class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                Volver
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5">Información</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                        <div>
                            <p class="text-slate-500">Proyecto</p>
                            <p class="font-semibold">{{ $tarea->proyecto->nombre ?? 'Sin proyecto' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Módulo</p>
                            <p class="font-semibold">{{ $tarea->modulo->nombre ?? 'Sin módulo' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Responsable</p>
                            <p class="font-semibold">
                                @if ($tarea->responsable)
                                    {{ $tarea->responsable->nombre }} {{ $tarea->responsable->apellido }}
                                @else
                                    Sin asignar
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500">Creado por</p>
                            <p class="font-semibold">
                                {{ $tarea->creador->nombre ?? '' }} {{ $tarea->creador->apellido ?? '' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500">Prioridad</p>
                            <p class="font-semibold">{{ ucfirst($tarea->prioridad) }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Estado</p>
                            <p class="font-semibold">{{ str_replace('_', ' ', ucfirst($tarea->estado)) }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Fecha inicio</p>
                            <p class="font-semibold">{{ $tarea->fecha_inicio?->format('d/m/Y') ?? 'No definida' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Fecha fin</p>
                            <p class="font-semibold">{{ $tarea->fecha_fin?->format('d/m/Y') ?? 'No definida' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5">Descripción</h2>
                    <p class="text-sm text-slate-700 leading-6">
                        {{ $tarea->descripcion ?? 'No se registró descripción.' }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5">Bugs relacionados</h2>

                    <div class="space-y-3">
                        @forelse ($tarea->bugs as $bug)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                                <div>
                                    <p class="font-semibold">#{{ $bug->id }} - {{ $bug->titulo }}</p>
                                    <p class="text-sm text-slate-500">
                                        Estado: {{ str_replace('_', ' ', ucfirst($bug->estado)) }}
                                    </p>
                                </div>

                                <a href="{{ route('bugs.show', $bug) }}"
                                    class="rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                    Ver bug
                                </a>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">
                                Esta tarea todavía no tiene bugs relacionados.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @if ($puedeCambiarEstado)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-5">Cambiar estado</h2>

                        <form action="{{ route('tareas.estado', $tarea) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PATCH')

                            <select name="estado"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                required>
                                <option value="pendiente" {{ $tarea->estado === 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="en_progreso" {{ $tarea->estado === 'en_progreso' ? 'selected' : '' }}>
                                    En progreso
                                </option>
                                <option value="completado" {{ $tarea->estado === 'completado' ? 'selected' : '' }}>
                                    Completado
                                </option>
                            </select>

                            <button type="submit"
                                class="w-full rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                                Guardar estado
                            </button>
                        </form>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4">Resumen</h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Estado</span>
                            <span class="font-semibold">{{ str_replace('_', ' ', ucfirst($tarea->estado)) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Prioridad</span>
                            <span class="font-semibold">{{ ucfirst($tarea->prioridad) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Bugs</span>
                            <span class="font-semibold">{{ $tarea->bugs->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
