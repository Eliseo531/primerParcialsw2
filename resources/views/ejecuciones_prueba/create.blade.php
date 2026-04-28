@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Ejecutar caso de prueba</h1>
            <p class="text-sm text-slate-500 mt-1">
                Caso: <span class="font-semibold">{{ $casoPrueba->titulo }}</span>
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
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-slate-500">Proyecto</p>
                    <p class="font-semibold text-slate-800">{{ $casoPrueba->proyecto->nombre ?? 'Sin proyecto' }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Módulo</p>
                    <p class="font-semibold text-slate-800">{{ $casoPrueba->modulo->nombre ?? 'Sin módulo' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-slate-500">Pasos</p>
                    <p class="font-semibold text-slate-800 whitespace-pre-line">{{ $casoPrueba->pasos }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-slate-500">Resultado esperado</p>
                    <p class="font-semibold text-slate-800 whitespace-pre-line">{{ $casoPrueba->resultado_esperado }}</p>
                </div>
            </div>

            <form action="{{ route('casos-prueba.ejecuciones.store', $casoPrueba) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Resultado</label>
                    <select name="resultado"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        required>
                        <option value="">Seleccione resultado</option>
                        <option value="OK" {{ old('resultado') === 'OK' ? 'selected' : '' }}>OK</option>
                        <option value="FAIL" {{ old('resultado') === 'FAIL' ? 'selected' : '' }}>FAIL</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        placeholder="Describe el resultado real observado...">{{ old('observaciones') }}</textarea>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        Guardar ejecución
                    </button>

                    <a href="{{ route('casos-prueba.ejecuciones.index', $casoPrueba) }}"
                        class="inline-flex items-center rounded-xl bg-slate-200 px-6 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
