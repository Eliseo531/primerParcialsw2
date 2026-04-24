<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Calidad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-slate-800">Iniciar sesión</h1>
                <p class="text-slate-500 mt-2">Sistema de gestión de calidad de software</p>
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

            <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                        Correo electrónico
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        placeholder="admin@calidad.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Contraseña
                    </label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        placeholder="********">
                </div>

                <div class="flex items-center gap-3">
                    <input id="remember" type="checkbox" name="remember"
                        class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                    <label for="remember" class="text-sm text-slate-600">
                        Recordarme
                    </label>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-cyan-600 px-6 py-3 text-white font-semibold hover:bg-cyan-700 transition">
                    Entrar
                </button>
            </form>

            <div class="mt-6 rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                <p class="font-semibold mb-1">Usuario de prueba</p>
                <p>Email: admin@calidad.com</p>
                <p>Contraseña: 12345678</p>
            </div>
        </div>
    </div>
</body>

</html>
