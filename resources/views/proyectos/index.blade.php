@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-800">Proyectos</h1>

        <a href="{{ route('proyectos.create') }}"
           class="rounded-xl bg-blue-600 px-4 py-2 text-white text-sm font-semibold hover:bg-blue-700 transition">
            + Nuevo Proyecto
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="text-left">
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Creador</th>
                    <th class="p-4">Miembros</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($proyectos as $proyecto)
                <tr class="border-t hover:bg-slate-50 transition">
                    <td class="p-4 font-medium text-slate-800">{{ $proyecto->nombre }}</td>
                    <td class="p-4">{{ $proyecto->estado }}</td>
                    <td class="p-4">{{ $proyecto->creador->nombre ?? '' }}</td>
                     <td class="p-4">
        @foreach($proyecto->miembros as $miembro)
            <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs mr-1">
                {{ $miembro->nombre }}
            </span>
        @endforeach

        @if($proyecto->miembros->isEmpty())
            <span class="text-gray-400 text-xs">Sin miembros</span>
        @endif
    </td>

                    <td class="p-4 flex gap-2">

                        <a href="{{ route('proyectos.edit', $proyecto) }}"
                           class="rounded-lg bg-yellow-500 px-3 py-1.5 text-white text-xs font-semibold hover:bg-yellow-600 transition">
                            Editar
                        </a>

                        <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="rounded-lg bg-red-500 px-3 py-1.5 text-white text-xs font-semibold hover:bg-red-600 transition">
                                Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection