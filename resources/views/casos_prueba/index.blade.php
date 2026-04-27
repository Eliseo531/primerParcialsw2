@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Gestión de Pruebas</h1>
                <p class="text-sm text-slate-500 mt-1">Casos de prueba registrados en el sistema.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pruebas.historial') }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Historial de ejecuciones
                </a>
                @if(in_array(auth()->user()->rol->nombre, ['Tester', 'Administrador']))
                    <a href="{{ route('pruebas.create') }}"
                       class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        + Nuevo caso
                    </a>
                @endif
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtro por proyecto --}}
        <form method="GET" action="{{ route('pruebas.index') }}"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-4 flex flex-wrap gap-4 items-end">

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Proyecto</label>
                <select name="proyecto_id"
                        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Todos</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" {{ request('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                            {{ $proyecto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                    class="rounded-xl bg-slate-800 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition">
                Filtrar
            </button>

            @if(request()->filled('proyecto_id'))
                <a href="{{ route('pruebas.index') }}"
                   class="rounded-xl border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Limpiar
                </a>
            @endif
        </form>

        {{-- Tabla --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">ID</th>
                            <th class="px-6 py-4 font-semibold">Nombre</th>
                            <th class="px-6 py-4 font-semibold">Proyecto</th>
                            <th class="px-6 py-4 font-semibold text-center">Pasos</th>
                            <th class="px-6 py-4 font-semibold text-center">Ejecuciones</th>
                            <th class="px-6 py-4 font-semibold">Creado por</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($casos as $caso)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-slate-500">#{{ $caso->id }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800 max-w-xs truncate">
                                    {{ $caso->nombre }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $caso->proyecto->nombre ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $caso->pasos_count }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $caso->ejecuciones_count }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $caso->creador->nombre_completo ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('pruebas.show', $caso) }}"
                                           class="inline-flex items-center rounded-lg bg-slate-800 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                            Ver
                                        </a>
                                        @if(in_array(auth()->user()->rol->nombre, ['Tester', 'Administrador']))
                                            <a href="{{ route('pruebas.edit', $caso) }}"
                                               class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                                Editar
                                            </a>
                                            <a href="{{ route('pruebas.ejecutar', $caso) }}"
                                               class="inline-flex items-center rounded-lg bg-cyan-600 px-3 py-2 text-xs font-semibold text-white hover:bg-cyan-700 transition">
                                                Ejecutar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400">
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
