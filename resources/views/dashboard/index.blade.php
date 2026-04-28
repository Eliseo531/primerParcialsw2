@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard de calidad</h1>
            <p class="text-sm text-slate-500 mt-1">
                Resumen ejecutivo del estado de calidad del software.
            </p>
        </div>

        {{-- KPIs PRINCIPALES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Proyectos</p>
                        <h3 class="text-4xl font-bold text-slate-800 mt-2">
                            {{ $stats['total_proyectos'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-cyan-100 flex items-center justify-center text-2xl">
                        📁
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">Proyectos registrados</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Bugs abiertos</p>
                        <h3 class="text-4xl font-bold text-red-600 mt-2">
                            {{ $stats['bugs_abiertos'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-red-100 flex items-center justify-center text-2xl">
                        🐞
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">Pendientes de solución</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Bugs cerrados</p>
                        <h3 class="text-4xl font-bold text-green-600 mt-2">
                            {{ $stats['bugs_cerrados'] ?? 0 }}
                        </h3>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                        ✅
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">Errores corregidos</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Tasa pruebas OK</p>
                        <h3 class="text-4xl font-bold text-cyan-600 mt-2">
                            {{ $stats['tasa_pruebas'] ?? 0 }}%
                        </h3>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-cyan-100 flex items-center justify-center text-2xl">
                        🧪
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">Éxito de pruebas ejecutadas</p>
            </div>
        </div>

        {{-- KPIs SECUNDARIOS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-2xl bg-gradient-to-r from-red-500 to-red-700 p-6 text-white shadow-sm">
                <p class="text-sm opacity-90">Pruebas fallidas</p>
                <h3 class="text-4xl font-bold mt-2">
                    {{ $stats['pruebas_fail'] ?? 0 }}
                </h3>
                <p class="text-xs opacity-80 mt-4">Casos que requieren revisión</p>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-green-500 to-green-700 p-6 text-white shadow-sm">
                <p class="text-sm opacity-90">Pruebas correctas</p>
                <h3 class="text-4xl font-bold mt-2">
                    {{ $stats['pruebas_ok'] ?? 0 }}
                </h3>
                <p class="text-xs opacity-80 mt-4">Validaciones exitosas</p>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-yellow-400 to-orange-500 p-6 text-white shadow-sm">
                <p class="text-sm opacity-90">Recomendaciones pendientes</p>
                <h3 class="text-4xl font-bold mt-2">
                    {{ $stats['recomendaciones_pendientes'] ?? 0 }}
                </h3>
                <p class="text-xs opacity-80 mt-4">Acciones de mejora sugeridas</p>
            </div>
        </div>

        {{-- GRÁFICAS --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-6">Bugs por estado</h3>
                <div class="h-[320px]">
                    <canvas id="bugsEstadoChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-6">Pruebas OK / FAIL</h3>
                <div class="h-[320px]">
                    <canvas id="pruebasChart"></canvas>
                </div>
            </div>
        </div>

        {{-- TABLAS / RESUMEN --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Últimos bugs</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-slate-600">
                                <th class="px-6 py-4">Título</th>
                                <th class="px-6 py-4">Proyecto</th>
                                <th class="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimosBugs as $bug)
                                <tr class="border-t border-slate-200">
                                    <td class="px-6 py-4 font-medium">
                                        {{ $bug->titulo }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $bug->proyecto->nombre ?? 'Sin proyecto' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ str_replace('_', ' ', ucfirst($bug->estado)) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                        No hay bugs registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-5">Recomendaciones pendientes</h3>

                <div class="space-y-4">
                    @forelse ($recomendaciones as $recomendacion)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="font-semibold text-slate-800">
                                {{ $recomendacion->proyecto->nombre ?? 'Sin proyecto' }}
                            </p>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $recomendacion->descripcion }}
                            </p>
                            <span
                                class="inline-flex mt-3 rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                {{ ucfirst($recomendacion->prioridad) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No hay recomendaciones pendientes.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        window.dashboardData = {
            bugsEstado: @json($bugsPorEstado ?? []),
            pruebas: @json($pruebasResultados ?? []),
        };
    </script>
@endsection
