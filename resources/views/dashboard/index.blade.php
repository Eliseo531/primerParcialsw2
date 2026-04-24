@extends('layouts.dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-r from-cyan-300 to-cyan-600">
            <p class="text-sm opacity-90">Total Bugs</p>
            <h3 class="text-4xl font-bold mt-2">{{ $stats['total_bugs'] }}</h3>
            <p class="text-xs mt-4 opacity-80">Calidad general del sistema</p>
        </div>

        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-r from-teal-300 to-teal-600">
            <p class="text-sm opacity-90">Bugs Abiertos</p>
            <h3 class="text-4xl font-bold mt-2">{{ $stats['bugs_abiertos'] }}</h3>
            <p class="text-xs mt-4 opacity-80">Pendientes de solución</p>
        </div>

        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-r from-lime-300 to-lime-500">
            <p class="text-sm opacity-90">Bugs Cerrados</p>
            <h3 class="text-4xl font-bold mt-2">{{ $stats['bugs_cerrados'] }}</h3>
            <p class="text-xs mt-4 opacity-80">Corregidos por desarrollo</p>
        </div>

        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-r from-sky-700 to-cyan-900">
            <p class="text-sm opacity-90">Pruebas OK</p>
            <h3 class="text-4xl font-bold mt-2">{{ $stats['tasa_pruebas'] }}%</h3>
            <p class="text-xs mt-4 opacity-80">Tasa de éxito de pruebas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Bugs por mes</h3>
                <button class="text-slate-400 hover:text-slate-600">⋮</button>
            </div>
            <div class="h-[320px]">
                <canvas id="bugsChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Resultado de pruebas</h3>
                <button class="text-slate-400 hover:text-slate-600">⋮</button>
            </div>
            <div class="h-[320px] flex items-center justify-center">
                <canvas id="testsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Bugs recientes</h3>
                <button class="text-slate-400 hover:text-slate-600">⋮</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="pb-3">ID</th>
                            <th class="pb-3">Título</th>
                            <th class="pb-3">Módulo</th>
                            <th class="pb-3">Severidad</th>
                            <th class="pb-3">Estado</th>
                            <th class="pb-3">Asignado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentBugs as $bug)
                            <tr class="border-b last:border-0">
                                <td class="py-4">#{{ $bug['id'] }}</td>
                                <td class="py-4 font-medium">{{ $bug['titulo'] }}</td>
                                <td class="py-4">{{ $bug['modulo'] }}</td>
                                <td class="py-4">
                                    @if ($bug['severidad'] === 'Alta')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            {{ $bug['severidad'] }}
                                        </span>
                                    @elseif ($bug['severidad'] === 'Media')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            {{ $bug['severidad'] }}
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            {{ $bug['severidad'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $bug['estado'] }}
                                    </span>
                                </td>
                                <td class="py-4">{{ $bug['asignado'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold">Actividad reciente</h3>
                <button class="text-slate-400 hover:text-slate-600">⋮</button>
            </div>

            <div class="space-y-4">
                @foreach ($activities as $activity)
                    <div class="flex gap-3 items-start">
                        <div class="mt-1 h-3 w-3 rounded-full bg-cyan-500"></div>
                        <p class="text-sm text-slate-600 leading-6">{{ $activity }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')
@endsection
