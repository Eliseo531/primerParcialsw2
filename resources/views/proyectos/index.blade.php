@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Proyectos</h1>

            <a href="{{ route('proyectos.create') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-xl">
                + Nuevo
            </a>
        </div>

        <div class="bg-white rounded-xl shadow">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4">Creador</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($proyectos as $proyecto)
                        <tr class="border-t">
                            <td class="p-4">{{ $proyecto->nombre }}</td>
                            <td class="p-4">{{ $proyecto->estado }}</td>
                            <td class="p-4">
                                {{ $proyecto->creador->nombre ?? '' }}
                            </td>

                            <td class="p-4 text-right">
                                <a href="{{ route('proyectos.miembros', $proyecto) }}"
                                    class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                    Miembros
                                </a>

                                <a href="{{ route('proyectos.modulos.index', $proyecto) }}"
                                    class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-700 transition">
                                    Módulos
                                </a>
                            </td>

                            <a href="{{ route('proyectos.modulos.index', $proyecto) }}"
                                class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-xs font-semibold text-white hover:bg-cyan-700 transition">
                                Módulos
                            </a>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
