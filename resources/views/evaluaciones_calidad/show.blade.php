@extends('layouts.dashboard')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detalle de evaluación</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Proyecto: {{ $evaluacion->proyecto->nombre ?? 'Sin proyecto' }}
                </p>
            </div>

            <a href="{{ route('evaluaciones-calidad.index') }}"
                class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                Volver
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <p class="text-sm text-slate-500">Usabilidad</p>
                <h2 class="text-3xl font-bold text-slate-800">{{ $evaluacion->usabilidad }}%</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <p class="text-sm text-slate-500">Rendimiento</p>
                <h2 class="text-3xl font-bold text-slate-800">{{ $evaluacion->rendimiento }}%</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <p class="text-sm text-slate-500">Seguridad</p>
                <h2 class="text-3xl font-bold text-slate-800">{{ $evaluacion->seguridad }}%</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <p class="text-sm text-slate-500">Índice global</p>
                <h2 class="text-3xl font-bold text-cyan-700">{{ $evaluacion->indice_calidad_global }}%</h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Información general</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-slate-500">Proyecto</p>
                    <p class="font-semibold">{{ $evaluacion->proyecto->nombre ?? 'Sin proyecto' }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Evaluado por</p>
                    <p class="font-semibold">
                        {{ $evaluacion->evaluador->nombre ?? '' }} {{ $evaluacion->evaluador->apellido ?? '' }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Fecha evaluación</p>
                    <p class="font-semibold">{{ $evaluacion->fecha_evaluacion?->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Observaciones</h2>

            <p class="text-sm text-slate-600 leading-6">
                {{ $evaluacion->observaciones ?? 'No se registraron observaciones.' }}
            </p>
        </div>
    </div>
@endsection
