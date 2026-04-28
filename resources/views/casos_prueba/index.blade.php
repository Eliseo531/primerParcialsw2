@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Casos de prueba</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Lista de casos de prueba definidos para los proyectos.
                </p>
            </div>

            @if (in_array(auth()->user()->rol->nombre ?? '', ['Administrador', 'Tester']))
                <a href="{{ route('casos-prueba.create') }}"
                    class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    + Nuevo caso
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
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold">Creado por</th>
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($casos as $caso)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">{{ $caso->id }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $caso->titulo }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $caso->proyecto->nombre ?? 'Sin proyecto' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $caso->modulo->nombre ?? 'Sin módulo' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($caso->estado === 'activo')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Activo
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    {{ $caso->creador->nombre ?? '' }} {{ $caso->creador->apellido ?? '' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $caso->created_at?->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('casos-prueba.ejecuciones.index', $caso) }}"
                                            class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                            Ejecuciones
                                        </a>

                                        @if (in_array(auth()->user()->rol->nombre ?? '', ['Administrador', 'Tester']))
                                            <a href="{{ route('casos-prueba.ejecuciones.create', $caso) }}"
                                                class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-700 transition">
                                                Ejecutar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    No hay casos de prueba registrados todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $casos->links() }}
            </div>
        </div>
    </div>
@endsection
