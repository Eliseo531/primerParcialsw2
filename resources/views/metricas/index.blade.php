@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Métricas de calidad</h1>
            <p class="text-sm text-slate-500 mt-1">
                Indicadores automáticos basados en bugs, pruebas y módulos del proyecto.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            @foreach ($proyectos as $proyecto)
                @php
                    $metrica = $proyecto->metricas->sortByDesc('fecha_calculo')->first();
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">{{ $proyecto->nombre }}</h2>
                            <p class="text-sm text-slate-500">
                                Último cálculo:
                                {{ $metrica?->fecha_calculo?->format('d/m/Y H:i') ?? 'Sin calcular' }}
                            </p>
                        </div>

                        <form action="{{ route('metricas.calcular', $proyecto) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                                Calcular métricas
                            </button>
                        </form>
                    </div>

                    @if ($metrica)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="rounded-xl bg-slate-100 p-5">
                                <p class="text-sm text-slate-500">Total bugs</p>
                                <h3 class="text-3xl font-bold text-slate-800">{{ $metrica->total_bugs }}</h3>
                            </div>

                            <div class="rounded-xl bg-red-100 p-5">
                                <p class="text-sm text-red-600">Bugs abiertos</p>
                                <h3 class="text-3xl font-bold text-red-700">{{ $metrica->bugs_abiertos }}</h3>
                            </div>

                            <div class="rounded-xl bg-blue-100 p-5">
                                <p class="text-sm text-blue-600">En proceso</p>
                                <h3 class="text-3xl font-bold text-blue-700">{{ $metrica->bugs_en_proceso }}</h3>
                            </div>

                            <div class="rounded-xl bg-green-100 p-5">
                                <p class="text-sm text-green-600">Bugs cerrados</p>
                                <h3 class="text-3xl font-bold text-green-700">{{ $metrica->bugs_cerrados }}</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <div class="rounded-xl bg-cyan-100 p-5">
                                <p class="text-sm text-cyan-600">Total pruebas</p>
                                <h3 class="text-3xl font-bold text-cyan-700">{{ $metrica->total_pruebas }}</h3>
                            </div>

                            <div class="rounded-xl bg-green-100 p-5">
                                <p class="text-sm text-green-600">Pruebas OK</p>
                                <h3 class="text-3xl font-bold text-green-700">{{ $metrica->pruebas_ok }}</h3>
                            </div>

                            <div class="rounded-xl bg-red-100 p-5">
                                <p class="text-sm text-red-600">Pruebas FAIL</p>
                                <h3 class="text-3xl font-bold text-red-700">{{ $metrica->pruebas_fail }}</h3>
                            </div>

                            <div class="rounded-xl bg-yellow-100 p-5">
                                <p class="text-sm text-yellow-700">Tasa éxito</p>
                                <h3 class="text-3xl font-bold text-yellow-800">{{ $metrica->tasa_exito_pruebas }}%</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="rounded-xl bg-slate-100 p-5">
                                <p class="text-sm text-slate-500">Tiempo promedio de resolución</p>
                                <h3 class="text-3xl font-bold text-slate-800">
                                    {{ $metrica->tiempo_promedio_resolucion }} h
                                </h3>
                            </div>

                            <div class="rounded-xl bg-slate-100 p-5">
                                <p class="text-sm text-slate-500">Densidad de defectos</p>
                                <h3 class="text-3xl font-bold text-slate-800">
                                    {{ $metrica->densidad_defectos }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    Bugs por módulo registrado.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl bg-slate-100 p-6 text-slate-500">
                            Este proyecto todavía no tiene métricas calculadas.
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
