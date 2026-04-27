@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Historial de ejecuciones</h1>
                <p class="text-sm text-slate-500 mt-1">Todas las ejecuciones de casos de prueba registradas.</p>
            </div>
            <a href="{{ route('pruebas.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Casos de prueba
            </a>
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold">Caso de prueba</th>
                            <th class="px-6 py-4 font-semibold">Ejecutado por</th>
                            <th class="px-6 py-4 font-semibold">Resultado</th>
                            <th class="px-6 py-4 font-semibold">Observaciones</th>
                            <th class="px-6 py-4 font-semibold">Bugs vinculados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ejecuciones as $ejecucion)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap">
                                    {{ $ejecucion->fecha_ejecucion->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800">
                                    <a href="{{ route('pruebas.show', $ejecucion->caso) }}"
                                       class="hover:text-cyan-700 transition">
                                        {{ $ejecucion->caso->nombre ?? '—' }}
                                    </a>
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
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
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
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    No hay ejecuciones registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $ejecuciones->links() }}
            </div>
        </div>
    </div>
@endsection
