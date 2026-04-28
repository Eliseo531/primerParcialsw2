@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Miembros del proyecto</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Proyecto: <span class="font-semibold">{{ $proyecto->nombre }}</span>
                </p>
            </div>

            <a href="{{ route('proyectos.index') }}"
                class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                Volver
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl bg-red-100 border border-red-200 text-red-700 px-4 py-3">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-800 mb-4">Asignar nuevo miembro</h2>

                <form action="{{ route('proyectos.miembros.asignar', $proyecto) }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Usuario
                        </label>

                        <select name="usuario_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            required>
                            <option value="">Seleccione un usuario</option>

                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">
                                    {{ $usuario->nombre }} {{ $usuario->apellido }}
                                    - {{ $usuario->rol->nombre ?? 'Sin rol' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        Asignar miembro
                    </button>
                </form>
            </div>

            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-800">Miembros actuales</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-slate-600">
                                <th class="px-6 py-4 font-semibold">Nombre</th>
                                <th class="px-6 py-4 font-semibold">Email</th>
                                <th class="px-6 py-4 font-semibold">Rol</th>
                                <th class="px-6 py-4 font-semibold">Fecha asignación</th>
                                <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($proyecto->miembros as $miembro)
                                <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-800">
                                        {{ $miembro->nombre }} {{ $miembro->apellido }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $miembro->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700">
                                            {{ $miembro->rol->nombre ?? 'Sin rol' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ optional($miembro->pivot->fecha_asignacion)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('proyectos.miembros.quitar', [$proyecto, $miembro]) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Seguro que deseas quitar este miembro del proyecto?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-red-100 px-4 py-2 text-xs font-semibold text-red-700 hover:bg-red-200 transition">
                                                Quitar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                        Este proyecto todavía no tiene miembros asignados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
