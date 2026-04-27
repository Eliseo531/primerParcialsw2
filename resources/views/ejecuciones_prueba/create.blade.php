@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6 max-w-3xl">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ejecutar caso de prueba</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $caso->nombre }}</p>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Referencia: pasos del caso --}}
        @if($caso->pasos->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-4">Pasos a ejecutar</h2>
                <ol class="space-y-3">
                    @foreach($caso->pasos as $paso)
                        <li class="flex items-start gap-3">
                            <span class="shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                {{ $paso->orden }}
                            </span>
                            <span class="text-sm text-slate-700">{{ $paso->descripcion }}</span>
                        </li>
                    @endforeach
                </ol>

                @if($caso->resultado_esperado)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Resultado esperado</p>
                        <p class="text-sm text-slate-700 mt-1">{{ $caso->resultado_esperado }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Formulario de ejecución --}}
        <form action="{{ route('pruebas.ejecutar.store', $caso) }}" method="POST"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Resultado <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="resultado" value="OK"
                               {{ old('resultado') === 'OK' ? 'checked' : '' }}
                               id="resultado-ok"
                               class="h-4 w-4 text-green-600 focus:ring-green-500">
                        <span class="text-sm font-semibold text-green-700">OK — Pasó</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="resultado" value="FAIL"
                               {{ old('resultado') === 'FAIL' ? 'checked' : '' }}
                               id="resultado-fail"
                               class="h-4 w-4 text-red-600 focus:ring-red-500">
                        <span class="text-sm font-semibold text-red-700">FAIL — Falló</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Observaciones</label>
                <textarea name="observaciones" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                          placeholder="Notas adicionales sobre la ejecución...">{{ old('observaciones') }}</textarea>
            </div>

            {{-- Bugs vinculados (solo si FAIL) --}}
            @if($bugsAbiertos->isNotEmpty())
                <div id="seccion-bugs" class="{{ old('resultado') === 'FAIL' ? '' : 'hidden' }}">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Bugs relacionados con el fallo</label>
                    <p class="text-xs text-slate-500 mb-3">Selecciona los bugs abiertos que causaron el fallo (opcional).</p>
                    <div class="space-y-2 max-h-60 overflow-y-auto border border-slate-200 rounded-xl p-4 bg-slate-50">
                        @foreach($bugsAbiertos as $bug)
                            <label class="flex items-center gap-3 cursor-pointer hover:bg-white rounded-lg px-2 py-1 transition">
                                <input type="checkbox" name="bugs[]" value="{{ $bug->id }}"
                                       {{ in_array($bug->id, old('bugs', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-cyan-600 rounded focus:ring-cyan-500">
                                <span class="text-sm text-slate-700">
                                    <span class="font-semibold text-slate-500">#{{ $bug->id }}</span>
                                    {{ $bug->titulo }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="rounded-xl bg-cyan-600 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-700 transition">
                    Registrar ejecución
                </button>
                <a href="{{ route('pruebas.show', $caso) }}"
                   class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        const radioOk   = document.getElementById('resultado-ok');
        const radioFail = document.getElementById('resultado-fail');
        const seccion   = document.getElementById('seccion-bugs');

        function toggleBugs() {
            if (seccion) {
                seccion.classList.toggle('hidden', !radioFail.checked);
            }
        }

        if (radioOk)   radioOk.addEventListener('change', toggleBugs);
        if (radioFail) radioFail.addEventListener('change', toggleBugs);
    </script>
@endsection
