@extends('layouts.dashboard')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Crear tarea</h1>
            <p class="text-sm text-slate-500 mt-1">
                Registra una tarea y asígnala a un desarrollador.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-100 border border-red-200 text-red-700 px-4 py-3">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form action="{{ route('tareas.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Proyecto</label>
                        <select name="proyecto_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            required>
                            <option value="">Seleccione un proyecto</option>
                            @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}"
                                    {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                    {{ $proyecto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Módulo</label>
                        <select name="modulo_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">Sin módulo</option>
                            @foreach ($modulos as $modulo)
                                <option value="{{ $modulo->id }}" {{ old('modulo_id') == $modulo->id ? 'selected' : '' }}>
                                    {{ $modulo->nombre }} - {{ $modulo->proyecto->nombre ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Título</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Responsable</label>
                        <select name="responsable_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">Sin asignar</option>
                            @foreach ($desarrolladores as $desarrollador)
                                <option value="{{ $desarrollador->id }}"
                                    {{ old('responsable_id') == $desarrollador->id ? 'selected' : '' }}>
                                    {{ $desarrollador->nombre }} {{ $desarrollador->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Prioridad</label>
                        <select name="prioridad"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            required>
                            <option value="baja" {{ old('prioridad') === 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('prioridad', 'media') === 'media' ? 'selected' : '' }}>Media
                            </option>
                            <option value="alta" {{ old('prioridad') === 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
                        <input type="text" value="Pendiente automático"
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500"
                            disabled>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Fecha fin</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        Guardar tarea
                    </button>

                    <a href="{{ route('tareas.index') }}"
                        class="inline-flex items-center rounded-xl bg-slate-200 px-6 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
