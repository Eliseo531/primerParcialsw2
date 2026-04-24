@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('bugs.index') }}"
                   class="text-sm text-slate-400 hover:text-slate-600 transition">← Volver al listado</a>
                <h1 class="text-2xl font-bold text-slate-800 mt-1">Bug #{{ $bug->id }}</h1>
            </div>

            <div class="flex items-center gap-3">
                @if(auth()->user()->rol->nombre === 'Tester')
                    <a href="{{ route('bugs.edit', $bug) }}"
                       class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Editar
                    </a>
                @endif
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl bg-red-100 border border-red-200 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Detalle principal --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-5">

                    <div class="flex items-center gap-3">
                        @if($bug->severidad === 'alta')
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Alta</span>
                        @elseif($bug->severidad === 'media')
                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Media</span>
                        @else
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Baja</span>
                        @endif

                        @if($bug->estado === 'abierto')
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Abierto</span>
                        @elseif($bug->estado === 'en proceso')
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">En proceso</span>
                        @else
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Cerrado</span>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-slate-800">{{ $bug->titulo }}</h2>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Descripción</p>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $bug->descripcion }}</p>
                    </div>

                    @if($bug->pasos_reproducir)
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Pasos para reproducir</p>
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $bug->pasos_reproducir }}</p>
                        </div>
                    @endif

                    @if($bug->resultado_esperado || $bug->resultado_actual)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($bug->resultado_esperado)
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Resultado esperado</p>
                                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $bug->resultado_esperado }}</p>
                                </div>
                            @endif
                            @if($bug->resultado_actual)
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Resultado actual</p>
                                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $bug->resultado_actual }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Historial --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                    <h3 class="text-base font-bold text-slate-800 mb-5">Historial de cambios</h3>

                    @if($bug->historial->isEmpty())
                        <p class="text-sm text-slate-400">Sin historial registrado.</p>
                    @else
                        <ol class="relative border-l border-slate-200 space-y-6 ml-2">
                            @foreach($bug->historial as $entrada)
                                <li class="ml-6">
                                    <span class="absolute -left-2 flex h-4 w-4 items-center justify-center rounded-full bg-cyan-100 ring-4 ring-white">
                                        <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                                    </span>
                                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                                        <span class="font-semibold text-slate-600">
                                            {{ $entrada->usuario->nombre_completo ?? '—' }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ $entrada->fecha_cambio->format('d/m/Y H:i') }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-sm">
                                        @if($entrada->estado_anterior)
                                            <span class="text-slate-500">{{ ucfirst($entrada->estado_anterior) }}</span>
                                            <span class="text-slate-300">→</span>
                                        @endif
                                        <span class="font-semibold text-slate-700">{{ ucfirst($entrada->estado_nuevo) }}</span>
                                    </div>

                                    @if($entrada->comentario)
                                        <p class="text-sm text-slate-500 mt-1">{{ $entrada->comentario }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>

            {{-- Panel lateral --}}
            <div class="space-y-6">

                {{-- Metadatos --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-700">Información</h3>

                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Reportado por</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">
                            {{ $bug->reportador->nombre_completo ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Asignado a</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">
                            {{ $bug->asignado->nombre_completo ?? 'Sin asignar' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Fecha de reporte</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">
                            {{ $bug->fecha_reporte->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    @if($bug->fecha_resolucion)
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wide">Fecha de resolución</p>
                            <p class="text-sm font-medium text-slate-700 mt-0.5">
                                {{ $bug->fecha_resolucion->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wide">Tiempo de resolución</p>
                            <p class="text-sm font-medium text-slate-700 mt-0.5">
                                {{ number_format($bug->tiempo_resolucion_horas, 1) }} horas
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Asignar bug (Tester / Administrador) --}}
                @if(in_array(auth()->user()->rol->nombre, ['Tester', 'Administrador']) && $bug->estado !== 'cerrado')
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4">Asignar desarrollador</h3>
                        <form action="{{ route('bugs.asignar', $bug) }}" method="POST" class="space-y-3">
                            @csrf
                            <select name="asignado_a"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                <option value="">Sin asignar</option>
                                @foreach($desarrolladores as $dev)
                                    <option value="{{ $dev->id }}" {{ $bug->asignado_a == $dev->id ? 'selected' : '' }}>
                                        {{ $dev->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="w-full rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 transition">
                                Asignar
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Cambiar estado (Desarrollador asignado) --}}
                @if(auth()->user()->rol->nombre === 'Desarrollador' && $bug->asignado_a === auth()->id() && $bug->estado !== 'cerrado')
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-sm font-bold text-slate-700 mb-4">Cambiar estado</h3>
                        <form action="{{ route('bugs.cambiarEstado', $bug) }}" method="POST" class="space-y-3">
                            @csrf
                            <select name="estado"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                @if($bug->estado === 'abierto')
                                    <option value="en proceso">En proceso</option>
                                @endif
                                @if(in_array($bug->estado, ['abierto', 'en proceso']))
                                    <option value="cerrado">Cerrado</option>
                                @endif
                            </select>
                            <textarea name="comentario" rows="2" placeholder="Comentario (opcional)"
                                      class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"></textarea>
                            <button type="submit"
                                    class="w-full rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-cyan-700 transition">
                                Actualizar estado
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
