@extends('layouts.dashboard')

@section('content')
<div class="space-y-6 max-w-3xl">

    <h1 class="text-2xl font-bold text-slate-800">Crear Proyecto</h1>

    <form method="POST" action="{{ route('proyectos.store') }}"
          class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">

        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre</label>
            <input type="text" name="nombre"
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
            <textarea name="descripcion" rows="3"
                      class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400"></textarea>
        </div>

        <button class="rounded-xl bg-blue-600 px-6 py-3 text-white text-sm font-semibold hover:bg-blue-700 transition">
            Guardar
        </button>

    </form>

</div>
@endsection