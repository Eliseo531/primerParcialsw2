@extends('layouts.dashboard')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center">
            <h1 class="text-5xl font-bold text-red-600">403</h1>
            <h2 class="text-2xl font-bold text-slate-800 mt-4">Acceso denegado</h2>
            <p class="text-slate-500 mt-3">
                No tienes permiso para acceder a esta sección del sistema.
            </p>

            <a href="{{ route('dashboard') }}"
                class="inline-flex mt-6 rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                Volver al dashboard
            </a>
        </div>
    </div>
@endsection
