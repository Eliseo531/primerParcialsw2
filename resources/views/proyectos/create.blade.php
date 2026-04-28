@extends('layouts.dashboard')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Crear Proyecto</h1>

        <form action="{{ route('proyectos.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block mb-2">Nombre</label>
                <input type="text" name="nombre" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2">Descripción</label>
                <textarea name="descripcion" class="w-full border rounded-xl px-4 py-3"></textarea>
            </div>

            <div>
                <label class="block mb-2">Estado</label>
                <select name="estado" class="w-full border rounded-xl px-4 py-3">
                    <option value="activo">Activo</option>
                    <option value="finalizado">Finalizado</option>
                </select>
            </div>

            <button class="bg-cyan-600 text-white px-6 py-3 rounded-xl">
                Guardar
            </button>
        </form>
    </div>
@endsection
