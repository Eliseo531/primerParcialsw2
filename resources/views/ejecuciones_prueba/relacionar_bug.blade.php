@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Relacionar bug</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Ejecución #{{ $ejecucion->id }} - Caso: {{ $ejecucion->casoPrueba->titulo }}
                </p>
            </div>

            <a href="{{ route('casos-prueba.ejecuciones.index', $ejecucion->casoPrueba) }}"
                class="inline-flex items-center rounded-xl bg-slate-200 px-5 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-xl bg-red-100 border border-red-200 text-red-700 px-4 py-3">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-5">Información de la ejecución</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-slate-500">Proyecto</p>
                    <p class="font-semibold">{{ $ejecucion->casoPrueba->proyecto->nombre ?? 'Sin proyecto' }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Módulo</p>
                    <p class="font-semibold">{{ $ejecucion->casoPrueba->modulo->nombre ?? 'Sin módulo' }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Resultado</p>
                    @if ($ejecucion->resultado === 'FAIL')
                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                            FAIL
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            OK
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-slate-500">Fecha</p>
                    <p class="font-semibold">{{ $ejecucion->fecha_ejecucion?->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-lg font-semibold text-slate-800 mb-5">Seleccionar bug relacionado</h2>

            @if ($ejecucion->resultado !== 'FAIL')
                <div class="rounded-xl bg-yellow-100 border border-yellow-200 text-yellow-800 px-4 py-3">
                    Esta ejecución no falló. Solo las ejecuciones con resultado FAIL deberían relacionarse con bugs.
                </div>
            @else
                <form action="{{ route('ejecuciones-prueba.guardar-relacion-bug', $ejecucion) }}" method="POST"
                    class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Bug
                        </label>

                        <select name="bug_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            required>
                            <option value="">Seleccione un bug</option>

                            @foreach ($bugs as $bug)
                                <option value="{{ $bug->id }}">
                                    #{{ $bug->id }} - {{ $bug->titulo }}
                                    | {{ ucfirst($bug->severidad) }}
                                    | {{ str_replace('_', ' ', ucfirst($bug->estado)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        Relacionar bug
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-5">Bugs ya relacionados</h2>

            <div class="space-y-3">
                @forelse ($ejecucion->bugs as $bug)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                        <div>
                            <p class="font-semibold text-slate-800">#{{ $bug->id }} - {{ $bug->titulo }}</p>
                            <p class="text-sm text-slate-500">
                                Estado: {{ str_replace('_', ' ', ucfirst($bug->estado)) }}
                            </p>
                        </div>

                        <a href="{{ route('bugs.show', $bug) }}"
                            class="rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                            Ver bug
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">
                        Esta ejecución aún no tiene bugs relacionados.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
