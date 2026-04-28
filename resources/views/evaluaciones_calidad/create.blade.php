@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Nueva evaluación de calidad</h1>
            <p class="text-sm text-slate-500 mt-1">
                Registra valores de calidad del proyecto en una escala de 0 a 100.
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
            <form action="{{ route('evaluaciones-calidad.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Proyecto</label>
                    <select name="proyecto_id" id="proyecto_id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        required>
                        <option value="">Seleccione un proyecto</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Usabilidad</label>
                        <input type="number" name="usabilidad" id="usabilidad" min="0" max="100" step="0.01"
                            value="{{ old('usabilidad') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Rendimiento</label>
                        <input type="number" name="rendimiento" id="rendimiento" min="0" max="100"
                            step="0.01" value="{{ old('rendimiento') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            required>
                    </div>


                </div>

                <div class="rounded-xl bg-slate-100 border border-slate-200 p-5 text-sm text-slate-600">
                    <p class="font-semibold text-slate-800 mb-2">Cálculo del índice global</p>

                    <p>
                        El sistema combina datos manuales y automáticos para evaluar la calidad del proyecto.
                    </p>

                    <div class="mt-3 space-y-1">
                        <p><strong>Usabilidad 30%:</strong> valor ingresado por el evaluador.</p>
                        <p><strong>Rendimiento 20%:</strong> valor ingresado por el evaluador.</p>
                        <p><strong>Seguridad 20%:</strong> calculada automáticamente según bugs críticos.</p>
                        <p><strong>Pruebas 15%:</strong> basada en pruebas OK y FAIL.</p>
                        <p><strong>Bugs 15%:</strong> basada en bugs cerrados y pendientes.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div class="rounded-xl bg-blue-100 p-5">
                        <p class="text-sm text-blue-700">Seguridad (calculada)</p>
                        <h3 id="preview_seguridad" class="text-3xl font-bold text-blue-800">0%</h3>
                    </div>

                    <div class="rounded-xl bg-green-100 p-5">
                        <p class="text-sm text-green-700">Calidad de pruebas</p>
                        <h3 id="preview_pruebas" class="text-3xl font-bold text-green-800">0%</h3>
                    </div>

                    <div class="rounded-xl bg-yellow-100 p-5">
                        <p class="text-sm text-yellow-700">Calidad de bugs</p>
                        <h3 id="preview_bugs" class="text-3xl font-bold text-yellow-800">0%</h3>
                    </div>

                    <div class="rounded-xl bg-cyan-100 p-5">
                        <p class="text-sm text-cyan-700">Índice global</p>
                        <h3 id="preview_indice" class="text-3xl font-bold text-cyan-800">0%</h3>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('observaciones') }}</textarea>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                        Guardar evaluación
                    </button>

                    <a href="{{ route('evaluaciones-calidad.index') }}"
                        class="inline-flex items-center rounded-xl bg-slate-200 px-6 py-3 text-slate-700 font-semibold hover:bg-slate-300 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const proyectoSelect = document.getElementById('proyecto_id');
        const usabilidadInput = document.getElementById('usabilidad');
        const rendimientoInput = document.getElementById('rendimiento');

        async function calcularPreviewCalidad() {
            const proyectoId = proyectoSelect?.value;
            const usabilidad = usabilidadInput?.value || 0;
            const rendimiento = rendimientoInput?.value || 0;

            if (!proyectoId) return;

            const response = await fetch("{{ route('evaluaciones-calidad.preview') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    proyecto_id: proyectoId,
                    usabilidad: usabilidad,
                    rendimiento: rendimiento
                })
            });

            const data = await response.json();

            document.getElementById('preview_seguridad').textContent = data.seguridad + '%';
            document.getElementById('preview_pruebas').textContent = data.calidad_pruebas + '%';
            document.getElementById('preview_bugs').textContent = data.calidad_bugs + '%';
            document.getElementById('preview_indice').textContent = data.indice_global + '%';
        }

        proyectoSelect?.addEventListener('change', calcularPreviewCalidad);
        usabilidadInput?.addEventListener('input', calcularPreviewCalidad);
        rendimientoInput?.addEventListener('input', calcularPreviewCalidad);
    </script>
@endsection
