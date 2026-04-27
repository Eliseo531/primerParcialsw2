@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $caso->nombre }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Caso #{{ $caso->id }} &middot; Proyecto: {{ $caso->proyecto->nombre ?? '—' }} &middot;
                    Creado por {{ $caso->creador->nombre_completo ?? '—' }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                @if(in_array(auth()->user()->rol->nombre, ['Tester', 'Administrador']))
                    <a href="{{ route('pruebas.ejecutar', $caso) }}"
                       class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        Ejecutar
                    </a>
                    <a href="{{ route('pruebas.edit', $caso) }}"
                       class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Editar
                    </a>
                @endif
                <a href="{{ route('pruebas.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Volver
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Detalle del caso --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Descripción</h2>
                <p class="text-sm text-slate-600">{{ $caso->descripcion ?? 'Sin descripción.' }}</p>

                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Condiciones previas</h2>
                <p class="text-sm text-slate-600">{{ $caso->condiciones ?? 'Sin condiciones.' }}</p>

                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Resultado esperado</h2>
                <p class="text-sm text-slate-600">{{ $caso->resultado_esperado }}</p>
            </div>

            {{-- Pasos --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-4">Pasos de prueba</h2>
                @if($caso->pasos->isEmpty())
                    <p class="text-sm text-slate-400">Sin pasos definidos.</p>
                @else
                    <ol class="space-y-3">
                        @foreach($caso->pasos as $paso)
                            <li class="flex items-start gap-3">
                                <span class="shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-cyan-100 text-cyan-700 text-xs font-bold">
                                    {{ $paso->orden }}
                                </span>
                                <span class="text-sm text-slate-700">{{ $paso->descripcion }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>

        {{-- Historial de ejecuciones --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Historial de ejecuciones</h2>
            </div>

            @if($caso->ejecuciones->isEmpty())
                <div class="px-6 py-10 text-center text-slate-400 text-sm">
                    Este caso aún no ha sido ejecutado.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-slate-600">
                                <th class="px-6 py-4 font-semibold">Fecha</th>
                                <th class="px-6 py-4 font-semibold">Ejecutado por</th>
                                <th class="px-6 py-4 font-semibold">Resultado</th>
                                <th class="px-6 py-4 font-semibold">Observaciones</th>
                                <th class="px-6 py-4 font-semibold">Bugs vinculados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($caso->ejecuciones as $ejecucion)
                                <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-slate-500 text-xs">
                                        {{ $ejecucion->fecha_ejecucion->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $ejecucion->ejecutor->nombre_completo ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($ejecucion->resultado === 'OK')
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">OK</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">FAIL</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 max-w-xs">
                                        {{ $ejecucion->observaciones ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($ejecucion->bugs->isEmpty())
                                            <span class="text-slate-400">—</span>
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($ejecucion->bugs as $bug)
                                                    <a href="{{ route('bugs.show', $bug) }}"
                                                       class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700 hover:bg-amber-200 transition">
                                                        #{{ $bug->id }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
