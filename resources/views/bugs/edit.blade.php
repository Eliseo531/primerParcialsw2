@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6 max-w-3xl">

        <div>
            <a href="{{ route('bugs.show', $bug) }}"
               class="text-sm text-slate-400 hover:text-slate-600 transition">← Volver al detalle</a>
            <h1 class="text-2xl font-bold text-slate-800 mt-1">Editar bug #{{ $bug->id }}</h1>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('bugs.update', $bug) }}" method="POST"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $bug->titulo) }}" maxlength="150"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                <textarea name="descripcion" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('descripcion', $bug->descripcion) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Severidad <span class="text-red-500">*</span></label>
                    <select name="severidad"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="baja"  {{ old('severidad', $bug->severidad) === 'baja'  ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ old('severidad', $bug->severidad) === 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta"  {{ old('severidad', $bug->severidad) === 'alta'  ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Asignar a desarrollador</label>
                    <select name="asignado_a"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Sin asignar</option>
                        @foreach($desarrolladores as $dev)
                            <option value="{{ $dev->id }}"
                                {{ old('asignado_a', $bug->asignado_a) == $dev->id ? 'selected' : '' }}>
                                {{ $dev->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pasos para reproducir</label>
                <textarea name="pasos_reproducir" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('pasos_reproducir', $bug->pasos_reproducir) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Resultado esperado</label>
                    <textarea name="resultado_esperado" rows="2"
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('resultado_esperado', $bug->resultado_esperado) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Resultado actual</label>
                    <textarea name="resultado_actual" rows="2"
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('resultado_actual', $bug->resultado_actual) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="rounded-xl bg-cyan-600 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-700 transition">
                    Guardar cambios
                </button>
                <a href="{{ route('bugs.show', $bug) }}"
                   class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
