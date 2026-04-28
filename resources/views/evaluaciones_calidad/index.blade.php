@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Evaluación de calidad</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Evaluaciones basadas en usabilidad, rendimiento y seguridad.
                </p>
            </div>

            <a href="{{ route('evaluaciones-calidad.create') }}"
                class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                + Nueva evaluación
            </a>
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
                            <th class="px-6 py-4 font-semibold">Proyecto</th>
                            <th class="px-6 py-4 font-semibold">Usabilidad</th>
                            <th class="px-6 py-4 font-semibold">Rendimiento</th>
                            <th class="px-6 py-4 font-semibold">Seguridad</th>
                            <th class="px-6 py-4 font-semibold">Índice global</th>
                            <th class="px-6 py-4 font-semibold">Evaluado por</th>
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($evaluaciones as $evaluacion)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">{{ $evaluacion->id }}</td>

                                <td class="px-6 py-4 font-medium">
                                    {{ $evaluacion->proyecto->nombre ?? 'Sin proyecto' }}
                                </td>

                                <td class="px-6 py-4">{{ $evaluacion->usabilidad }}%</td>
                                <td class="px-6 py-4">{{ $evaluacion->rendimiento }}%</td>
                                <td class="px-6 py-4">{{ $evaluacion->seguridad }}%</td>

                                <td class="px-6 py-4">
                                    @php
                                        $color =
                                            $evaluacion->indice_calidad_global >= 80
                                                ? 'bg-green-100 text-green-700'
                                                : ($evaluacion->indice_calidad_global >= 60
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700');
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $color }}">
                                        {{ $evaluacion->indice_calidad_global }}%
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $evaluacion->evaluador->nombre ?? '' }} {{ $evaluacion->evaluador->apellido ?? '' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $evaluacion->fecha_evaluacion?->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('evaluaciones-calidad.show', $evaluacion) }}"
                                        class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                                    No hay evaluaciones registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $evaluaciones->links() }}
            </div>
        </div>
    </div>
@endsection
