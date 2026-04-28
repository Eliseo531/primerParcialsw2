<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-800">
    @php
        $usuario = auth()->user();
        $rol = $usuario->rol->nombre ?? null;
    @endphp

    @php
        $seccion = request()->routeIs('dashboard')               ? 'dashboard'
                 : (request()->routeIs('proyectos.*')            ? 'proyectos'
                 : (request()->routeIs('tareas.*')               ? 'tareas'
                 : (request()->routeIs('bugs.*')                 ? 'bugs'
                 : (request()->routeIs('casos-prueba.*')         ? 'pruebas'
                 : (request()->routeIs('metricas.*')             ? 'metricas'
                 : (request()->routeIs('evaluaciones-calidad.*') ? 'calidad'
                 : (request()->routeIs('recomendaciones.*')      ? 'mejora'
                 : (request()->routeIs('usuarios.*')             ? 'usuarios' : ''))))))));
    @endphp

    <div class="min-h-screen flex">
        {{-- Sidebar fijo --}}
        <aside class="fixed top-0 left-0 h-screen w-72 bg-[#0b0f14] text-white flex flex-col justify-between shadow-2xl z-50 overflow-y-auto">
            <div>
                <div class="px-8 py-7 border-b border-white/10">
                    <h1 class="text-2xl font-bold tracking-wide">Calidad SW</h1>
                    <p class="text-xs text-slate-400 mt-1">{{ $rol ?? 'Sin rol' }}</p>
                </div>

                <nav class="mt-6 px-4 space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                            {{ $seccion === 'dashboard' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>▦</span>
                        <span>Dashboard</span>
                    </a>

                    @if (in_array($rol, ['Administrador', 'Tester', 'Desarrollador']))
                        <a href="{{ route('proyectos.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'proyectos' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Proyectos</span>
                        </a>
                    @endif

                    @if (in_array($rol, ['Administrador', 'Desarrollador']))
                        <a href="{{ route('tareas.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'tareas' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Tareas</span>
                        </a>
                    @endif

                    @if (in_array($rol, ['Administrador', 'Tester', 'Desarrollador']))
                        <a href="{{ route('bugs.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'bugs' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Bugs</span>
                        </a>
                    @endif

                    @if (in_array($rol, ['Administrador', 'Tester']))
                        <a href="{{ route('casos-prueba.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'pruebas' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Pruebas</span>
                        </a>
                    @endif

                    @if ($rol === 'Administrador')
                        <a href="{{ route('metricas.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'metricas' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Métricas</span>
                        </a>

                        <a href="{{ route('evaluaciones-calidad.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'calidad' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Calidad</span>
                        </a>

                        <a href="{{ route('recomendaciones.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'mejora' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Mejora continua</span>
                        </a>

                        <a href="{{ route('usuarios.index') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                {{ $seccion === 'usuarios' ? 'bg-white/10 text-white font-semibold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>•</span>
                            <span>Usuarios</span>
                        </a>
                    @endif
                </nav>
            </div>

            <div class="p-4 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 rounded-xl px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white transition text-left">
                        <span>⎋</span>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenido desplazado por el sidebar fijo --}}
        <main class="flex-1 min-h-screen ml-72">
            <header class="h-24 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">
                        Bienvenido, {{ $usuario->nombre ?? 'Usuario' }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Panel de control de calidad de software</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center bg-slate-100 rounded-full px-4 py-2 w-72">
                        <span class="text-slate-400 mr-2">⌕</span>
                        <input type="text" placeholder="Buscar" class="bg-transparent outline-none w-full text-sm">
                    </div>

                    <button class="relative bg-slate-100 rounded-full p-3 hover:bg-slate-200 transition">
                        🔔
                        <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500"></span>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-full bg-gradient-to-br from-amber-200 to-orange-400"></div>
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold">
                                {{ $usuario->nombre ?? '' }} {{ $usuario->apellido ?? '' }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ $rol ?? 'Sin rol' }}
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            <section class="p-8">
                @yield('content')
            </section>
        </main>
    </div>
</body>

</html>
