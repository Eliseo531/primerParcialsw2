@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Gestión de Bugs</h1>
                <p class="text-sm text-slate-500 mt-1">Listado de todos los bugs registrados en el sistema.</p>
            </div>

            @if(in_array(auth()->user()->rol->nombre, ['Tester', 'Administrador']))
                <a href="{{ route('bugs.create') }}"
                   class="inline-flex items-center rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    + Registrar bug
                </a>
            @endif
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtros --}}
        <form method="GET" action="{{ route('bugs.index') }}"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-4 flex flex-wrap gap-4 items-end">

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Severidad</label>
                <select name="severidad"
                        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Todas</option>
                    <option value="baja"  {{ request('severidad') === 'baja'  ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ request('severidad') === 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta"  {{ request('severidad') === 'alta'  ? 'selected' : '' }}>Alta</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</label>
                <select name="estado"
                        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Todos</option>
                    <option value="abierto"    {{ request('estado') === 'abierto'    ? 'selected' : '' }}>Abierto</option>
                    <option value="en proceso" {{ request('estado') === 'en proceso' ? 'selected' : '' }}>En proceso</option>
                    <option value="cerrado"    {{ request('estado') === 'cerrado'    ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>

            <button type="submit"
                    class="rounded-xl bg-slate-800 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition">
                Filtrar
            </button>

            @if(request()->hasAny(['severidad', 'estado']))
                <a href="{{ route('bugs.index') }}"
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
                            <th class="px-6 py-4 font-semibold">Título</th>
                            <th class="px-6 py-4 font-semibold">Severidad</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold">Reportado por</th>
                            <th class="px-6 py-4 font-semibold">Asignado a</th>
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bugs as $bug)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-slate-500">#{{ $bug->id }}</td>

                                <td class="px-6 py-4 font-medium text-slate-800 max-w-xs truncate">
                                    {{ $bug->titulo }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($bug->severidad === 'alta')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Alta</span>
                                    @elseif($bug->severidad === 'media')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Media</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Baja</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($bug->estado === 'abierto')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Abierto</span>
                                    @elseif($bug->estado === 'en proceso')
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">En proceso</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Cerrado</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $bug->reportador->nombre_completo ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $bug->asignado->nombre_completo ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $bug->fecha_reporte->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('bugs.show', $bug) }}"
                                       class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-slate-400">
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
