@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6 max-w-3xl">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Registrar bug</h1>
            <p class="text-sm text-slate-500 mt-1">Completa el formulario para registrar un nuevo bug.</p>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('bugs.store') }}" method="POST"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
            @csrf

            {{-- proyecto_id temporal: campo manual hasta que exista la tabla proyectos --}}
            {{-- TODO: reemplazar por un <select> con Proyecto::all() cuando exista el modelo Proyecto --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ID de Proyecto</label>
                <input type="number" name="proyecto_id" value="{{ old('proyecto_id') }}" min="1"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                       placeholder="Ingresa el ID del proyecto">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" maxlength="150"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                       placeholder="Descripción breve del bug">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                <textarea name="descripcion" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="Describe el bug con detalle">{{ old('descripcion') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Severidad <span class="text-red-500">*</span></label>
                    <select name="severidad"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Seleccionar...</option>
                        <option value="baja"  {{ old('severidad') === 'baja'  ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ old('severidad') === 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta"  {{ old('severidad') === 'alta'  ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Asignar a desarrollador</label>
                    <select name="asignado_a"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Sin asignar</option>
                        @foreach($desarrolladores as $dev)
                            <option value="{{ $dev->id }}" {{ old('asignado_a') == $dev->id ? 'selected' : '' }}>
                                {{ $dev->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pasos para reproducir</label>
                <textarea name="pasos_reproducir" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="1. Ir a... 2. Hacer clic en...">{{ old('pasos_reproducir') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Resultado esperado</label>
                    <textarea name="resultado_esperado" rows="2"
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                              placeholder="¿Qué debería ocurrir?">{{ old('resultado_esperado') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Resultado actual</label>
                    <textarea name="resultado_actual" rows="2"
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                              placeholder="¿Qué ocurre realmente?">{{ old('resultado_actual') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="rounded-xl bg-cyan-600 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-700 transition">
                    Registrar bug
                </button>
                <a href="{{ route('bugs.index') }}"
                   class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
