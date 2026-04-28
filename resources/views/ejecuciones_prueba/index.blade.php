@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Ejecuciones de prueba</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Caso: <span class="font-semibold">{{ $casoPrueba->titulo }}</span>
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('casos-prueba.index') }}"
                    class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                    Volver
                </a>

                <a href="{{ route('casos-prueba.ejecuciones.create', $casoPrueba) }}"
                    class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    + Ejecutar prueba
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-5">Información del caso</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-slate-500">Proyecto</p>
                    <p class="font-semibold">{{ $casoPrueba->proyecto->nombre ?? 'Sin proyecto' }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Módulo</p>
                    <p class="font-semibold">{{ $casoPrueba->modulo->nombre ?? 'Sin módulo' }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Resultado esperado</p>
                    <p class="font-semibold">{{ $casoPrueba->resultado_esperado }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Creado por</p>
                    <p class="font-semibold">
                        {{ $casoPrueba->creador->nombre ?? '' }} {{ $casoPrueba->creador->apellido ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">ID</th>
                            <th class="px-6 py-4 font-semibold">Resultado</th>
                            <th class="px-6 py-4 font-semibold">Observaciones</th>
                            <th class="px-6 py-4 font-semibold">Ejecutado por</th>
                            <th class="px-6 py-4 font-semibold">Fecha ejecución</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ejecuciones as $ejecucion)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">{{ $ejecucion->id }}</td>

                                <td class="px-6 py-4">
                                    @if ($ejecucion->resultado === 'OK')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            OK
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            FAIL
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $ejecucion->observaciones ?? 'Sin observaciones' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $ejecucion->ejecutor->nombre ?? '' }} {{ $ejecucion->ejecutor->apellido ?? '' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $ejecucion->fecha_ejecucion?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($ejecucion->resultado === 'FAIL')
                                        <a href="{{ route('ejecuciones-prueba.relacionar-bug', $ejecucion) }}"
                                            class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 transition">
                                            Relacionar bug
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">Sin acción</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    Este caso de prueba todavía no tiene ejecuciones registradas.
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
