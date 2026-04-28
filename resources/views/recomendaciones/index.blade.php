@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Mejora continua</h1>
            <p class="text-sm text-slate-500 mt-1">
                Recomendaciones generadas automáticamente a partir de bugs, pruebas y evaluaciones de calidad.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Generar recomendaciones</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($proyectos as $proyecto)
                    <div class="rounded-xl border border-slate-200 p-5">
                        <h3 class="font-semibold text-slate-800">{{ $proyecto->nombre }}</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Genera recomendaciones automáticas para este proyecto.
                        </p>

                        <form action="{{ route('recomendaciones.generar', $proyecto) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-cyan-600 px-5 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                                Generar
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">Recomendaciones registradas</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-6 py-4 font-semibold">ID</th>
                            <th class="px-6 py-4 font-semibold">Proyecto</th>
                            <th class="px-6 py-4 font-semibold">Módulo</th>
                            <th class="px-6 py-4 font-semibold">Tipo</th>
                            <th class="px-6 py-4 font-semibold">Descripción</th>
                            <th class="px-6 py-4 font-semibold">Prioridad</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold text-right">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recomendaciones as $recomendacion)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="px-6 py-4">#{{ $recomendacion->id }}</td>

                                <td class="px-6 py-4 font-medium">
                                    {{ $recomendacion->proyecto->nombre ?? 'Sin proyecto' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $recomendacion->modulo->nombre ?? 'General' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ str_replace('_', ' ', ucfirst($recomendacion->tipo ?? 'general')) }}
                                </td>

                                <td class="px-6 py-4 max-w-md">
                                    {{ $recomendacion->descripcion }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $prioridadColor = match ($recomendacion->prioridad) {
                                            'alta' => 'bg-red-100 text-red-700',
                                            'media' => 'bg-yellow-100 text-yellow-700',
                                            default => 'bg-green-100 text-green-700',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $prioridadColor }}">
                                        {{ ucfirst($recomendacion->prioridad) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $estadoColor = match ($recomendacion->estado) {
                                            'aplicada' => 'bg-green-100 text-green-700',
                                            'descartada' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $estadoColor }}">
                                        {{ ucfirst($recomendacion->estado) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $recomendacion->fecha_generacion?->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('recomendaciones.estado', $recomendacion) }}" method="POST"
                                        class="flex justify-end gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <select name="estado"
                                            class="rounded-lg border border-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                            <option value="pendiente"
                                                {{ $recomendacion->estado === 'pendiente' ? 'selected' : '' }}>
                                                Pendiente
                                            </option>
                                            <option value="aplicada"
                                                {{ $recomendacion->estado === 'aplicada' ? 'selected' : '' }}>
                                                Aplicada
                                            </option>
                                            <option value="descartada"
                                                {{ $recomendacion->estado === 'descartada' ? 'selected' : '' }}>
                                                Descartada
                                            </option>
                                        </select>

                                        <button type="submit"
                                            class="rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                            Guardar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                                    No hay recomendaciones registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $recomendaciones->links() }}
            </div>
        </div>
    </div>
@endsection
