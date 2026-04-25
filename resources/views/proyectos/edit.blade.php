@extends('layouts.dashboard')

@section('content')
<div class="space-y-6 max-w-3xl">

    <h1 class="text-2xl font-bold text-slate-800">Editar Proyecto</h1>

    <form method="POST" action="{{ route('proyectos.update', $proyecto) }}"
          class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">

        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre</label>
            <input type="text" name="nombre"
                   value="{{ $proyecto->nombre }}"
                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
            <textarea name="descripcion" rows="3"
                      class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400">{{ $proyecto->descripcion }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Estado</label>
            <select name="estado"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400">

                <option value="activo" {{ $proyecto->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ $proyecto->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>

            </select>
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">
                Asignar miembros
            </label>

            <select name="usuarios[]" multiple
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm">

                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}"
                        {{ $proyecto->miembros->contains($usuario->id) ? 'selected' : '' }}>
                        {{ $usuario->nombre }}
                    </option>
                @endforeach

            </select>
        </div>

        <button class="rounded-xl bg-green-600 px-6 py-3 text-white text-sm font-semibold hover:bg-green-700 transition">
            Actualizar
        </button>

    </form>

</div>
@endsection