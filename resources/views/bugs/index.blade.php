@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Bugs</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Lista de errores registrados en los proyectos.
                </p>
            </div>

            @if (in_array(auth()->user()->rol->nombre ?? '', ['Administrador', 'Tester']))
                <a href="{{ route('bugs.create') }}"
                    class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    + Nuevo bug
                </a>
            @endif
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
                            <th class="px-6 py-4 font-semibold">Título</th>
                            <th class="px-6 py-4 font-semibold">Proyecto</th>
                            <th class="px-6 py-4 font-semibold">Módulo</th>
                            <th class="px-6 py-4 font-semibold">Severidad</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold">Reportado por</th>
                            <th class="px-6 py-4 font-semibold">Asignado a</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bugs as $bug)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">{{ $bug->id }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $bug->titulo }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bug->proyecto->nombre ?? 'Sin proyecto' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bug->modulo->nombre ?? 'Sin módulo' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $severidadColor = match ($bug->severidad) {
                                            'critica' => 'bg-red-200 text-red-900',
                                            'alta' => 'bg-red-100 text-red-700',
                                            'media' => 'bg-yellow-100 text-yellow-700',
                                            default => 'bg-green-100 text-green-700',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $severidadColor }}">
                                        {{ ucfirst($bug->severidad) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $estadoColor = match ($bug->estado) {
                                            'cerrado' => 'bg-green-100 text-green-700',
                                            'en_proceso' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $estadoColor }}">
                                        {{ str_replace('_', ' ', ucfirst($bug->estado)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bug->reportero->nombre ?? 'Sin datos' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bug->asignado->nombre ?? 'Sin asignar' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('bugs.show', $bug) }}"
                                        class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                                    No hay bugs registrados todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $bugs->links() }}
            </div>
        </div>
    </div>
@endsection
