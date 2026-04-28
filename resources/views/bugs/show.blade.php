@extends('layouts.dashboard')

@section('content')
    @php
        $rol = auth()->user()->rol->nombre ?? null;
        $puedeCambiarEstado = in_array($rol, ['Administrador', 'Desarrollador']);
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detalle del bug {{ $bug->id }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $bug->titulo }}
                </p>
            </div>

            <a href="{{ route('bugs.index') }}"
                class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                Volver
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl bg-red-100 border border-red-200 text-red-700 px-4 py-3">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5">Información del bug</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                        <div>
                            <p class="text-slate-500">Proyecto</p>
                            <p class="font-semibold text-slate-800">{{ $bug->proyecto->nombre ?? 'Sin proyecto' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Módulo</p>
                            <p class="font-semibold text-slate-800">{{ $bug->modulo->nombre ?? 'Sin módulo' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Reportado por</p>
                            <p class="font-semibold text-slate-800">
                                {{ $bug->reportero->nombre ?? '' }} {{ $bug->reportero->apellido ?? '' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500">Asignado a</p>
                            <p class="font-semibold text-slate-800">
                                @if ($bug->asignado)
                                    {{ $bug->asignado->nombre }} {{ $bug->asignado->apellido }}
                                @else
                                    Sin asignar
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500">Severidad</p>
                            <p class="font-semibold text-slate-800">{{ ucfirst($bug->severidad) }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Estado</p>
                            <p class="font-semibold text-slate-800">
                                {{ str_replace('_', ' ', ucfirst($bug->estado)) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500">Fecha reporte</p>
                            <p class="font-semibold text-slate-800">
                                {{ $bug->fecha_reporte?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500">Fecha resolución</p>
                            <p class="font-semibold text-slate-800">
                                {{ $bug->fecha_resolucion?->format('d/m/Y H:i') ?? 'Pendiente' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5">Descripción</h2>

                    <div class="space-y-5 text-sm text-slate-700">
                        <div>
                            <p class="font-semibold text-slate-800 mb-1">Descripción</p>
                            <p>{{ $bug->descripcion }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500">Tarea relacionada</p>
                            <p class="font-semibold text-slate-800">
                                {{ $bug->tarea->titulo ?? 'Sin tarea' }}
                            </p>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-800 mb-1">Pasos para reproducir</p>
                            <p>{{ $bug->pasos_reproducir ?? 'No registrado' }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <p class="font-semibold text-slate-800 mb-1">Resultado esperado</p>
                                <p>{{ $bug->resultado_esperado ?? 'No registrado' }}</p>
                            </div>

                            <div>
                                <p class="font-semibold text-slate-800 mb-1">Resultado actual</p>
                                <p>{{ $bug->resultado_actual ?? 'No registrado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5">Historial de cambios</h2>

                    <div class="space-y-4">
                        @forelse ($bug->historial->sortByDesc('fecha_cambio') as $historial)
                            <div class="border-l-4 border-cyan-500 pl-4 py-2">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ str_replace('_', ' ', ucfirst($historial->estado_anterior ?? 'sin estado')) }}
                                    →
                                    {{ str_replace('_', ' ', ucfirst($historial->estado_nuevo)) }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    Por:
                                    {{ $historial->usuario->nombre ?? '' }}
                                    {{ $historial->usuario->apellido ?? '' }}
                                    -
                                    {{ $historial->fecha_cambio?->format('d/m/Y H:i') }}
                                </p>

                                @if ($historial->comentario)
                                    <p class="text-sm text-slate-600 mt-2">
                                        {{ $historial->comentario }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">
                                Todavía no hay cambios de estado registrados.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @if ($puedeCambiarEstado)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-5">Cambiar estado</h2>

                        <form action="{{ route('bugs.cambiarEstado', $bug) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Nuevo estado
                                </label>
                                <select name="estado"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                    required>
                                    <option value="abierto" {{ $bug->estado === 'abierto' ? 'selected' : '' }}>
                                        Abierto
                                    </option>
                                    <option value="en_proceso" {{ $bug->estado === 'en_proceso' ? 'selected' : '' }}>
                                        En proceso
                                    </option>
                                    <option value="cerrado" {{ $bug->estado === 'cerrado' ? 'selected' : '' }}>
                                        Cerrado
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Comentario
                                </label>
                                <textarea name="comentario" rows="4"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                    placeholder="Describe el cambio realizado..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                                Guardar cambio
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-3">Cambio de estado</h2>
                        <p class="text-sm text-slate-500">
                            Tu rol actual no tiene permiso para cambiar el estado del bug.
                        </p>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-4">Resumen</h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Estado actual</span>
                            <span class="font-semibold">{{ str_replace('_', ' ', ucfirst($bug->estado)) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Severidad</span>
                            <span class="font-semibold">{{ ucfirst($bug->severidad) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Tiempo resolución</span>
                            <span class="font-semibold">
                                {{ $bug->tiempo_resolucion_horas ? $bug->tiempo_resolucion_horas . ' h' : 'Pendiente' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Rol actual</span>
                            <span class="font-semibold">{{ $rol ?? 'Sin rol' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
