@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6 max-w-3xl">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Nuevo caso de prueba</h1>
            <p class="text-sm text-slate-500 mt-1">Completa el formulario para registrar un caso de prueba.</p>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('pruebas.store') }}" method="POST"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Proyecto <span class="text-red-500">*</span></label>
                <select name="proyecto_id"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Seleccionar proyecto...</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                            {{ $proyecto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="200"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                       placeholder="Nombre del caso de prueba">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="Descripción general del caso de prueba">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Condiciones previas</label>
                <textarea name="condiciones" rows="2"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="Condiciones o precondiciones necesarias">{{ old('condiciones') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Resultado esperado <span class="text-red-500">*</span></label>
                <textarea name="resultado_esperado" rows="2"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="¿Qué debería ocurrir al ejecutar este caso?">{{ old('resultado_esperado') }}</textarea>
            </div>

            {{-- Pasos de prueba --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-slate-700">Pasos de prueba</label>
                    <button type="button" id="agregar-paso"
                            class="rounded-lg border border-cyan-400 px-3 py-1.5 text-xs font-semibold text-cyan-700 hover:bg-cyan-50 transition">
                        + Agregar paso
                    </button>
                </div>

                <div id="pasos-container" class="space-y-3">
                    @if(old('pasos'))
                        @foreach(old('pasos') as $i => $paso)
                            <div class="paso-item flex items-start gap-3">
                                <span class="mt-2.5 text-xs font-semibold text-slate-400 w-6 text-right shrink-0 paso-numero">{{ $i + 1 }}</span>
                                <textarea name="pasos[{{ $i }}][descripcion]" rows="2"
                                          class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                                          placeholder="Descripción del paso">{{ $paso['descripcion'] }}</textarea>
                                <button type="button"
                                        class="mt-2 btn-quitar-paso text-slate-400 hover:text-red-500 transition text-lg leading-none">✕</button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="rounded-xl bg-cyan-600 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-700 transition">
                    Crear caso de prueba
                </button>
                <a href="{{ route('pruebas.index') }}"
                   class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        let pasoIndex = {{ old('pasos') ? count(old('pasos')) : 0 }};

        function crearPaso(index, valor) {
            const div = document.createElement('div');
            div.className = 'paso-item flex items-start gap-3';
            div.innerHTML = `
                <span class="mt-2.5 text-xs font-semibold text-slate-400 w-6 text-right shrink-0 paso-numero">${index + 1}</span>
                <textarea name="pasos[${index}][descripcion]" rows="2"
                          class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="Descripción del paso">${valor ?? ''}</textarea>
                <button type="button"
                        class="mt-2 btn-quitar-paso text-slate-400 hover:text-red-500 transition text-lg leading-none">✕</button>
            `;
            return div;
        }

        function renumerarPasos() {
            document.querySelectorAll('#pasos-container .paso-item').forEach((item, i) => {
                item.querySelector('.paso-numero').textContent = i + 1;
                item.querySelector('textarea').name = `pasos[${i}][descripcion]`;
            });
            pasoIndex = document.querySelectorAll('#pasos-container .paso-item').length;
        }

        document.getElementById('agregar-paso').addEventListener('click', function () {
            const container = document.getElementById('pasos-container');
            container.appendChild(crearPaso(pasoIndex));
            pasoIndex++;
        });

        document.getElementById('pasos-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-quitar-paso')) {
                e.target.closest('.paso-item').remove();
                renumerarPasos();
            }
        });
    </script>
@endsection
