@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Módulos del proyecto</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Proyecto: <span class="font-semibold">{{ $proyecto->nombre }}</span>
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('proyectos.index') }}"
                    class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                    Volver
                </a>

                <a href="{{ route('proyectos.modulos.create', $proyecto) }}"
                    class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    + Nuevo módulo
                </a>
            </div>
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
                            <th class="px-6 py-4 font-semibold">Nombre</th>
                            <th class="px-6 py-4 font-semibold">Descripción</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($modulos as $modulo)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">{{ $modulo->id }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $modulo->nombre }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $modulo->descripcion ?? 'Sin descripción' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($modulo->estado === 'activo')
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

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $modulo->created_at?->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('proyectos.modulos.edit', [$proyecto, $modulo]) }}"
                                        class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    Este proyecto todavía no tiene módulos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $modulos->links() }}
            </div>
        </div>
    </div>
@endsection
