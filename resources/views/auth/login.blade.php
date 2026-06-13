<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — LABOCLYPSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-900 to-blue-700">

{{-- Contenedor scrollable: el botón nunca queda tapado por el teclado móvil --}}
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm">

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                <img src="/img/logo.png" alt="Logo" class="w-12 h-12 rounded-full object-cover">
            </div>
            <h1 class="text-white text-3xl font-bold tracking-wide">LABOCLYPSA</h1>
            <p class="text-blue-200 text-sm mt-1">Sistema de Laboratorio Clínico</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl px-6 py-8 sm:px-8">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email" inputmode="email"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-base">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                        <input type="password" name="password" required
                            autocomplete="current-password"
                            class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-base">
                    </div>
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 mr-2 accent-blue-600">
                    <label for="remember" class="text-sm text-gray-600">Recordarme</label>
                </div>

                <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 active:bg-blue-900
                           text-white font-semibold py-3 rounded-lg transition text-base">
                    <i class="fas fa-sign-in-alt mr-2"></i>Ingresar
                </button>
            </form>
        </div>

        <p class="text-center text-blue-200 text-xs mt-6">
            © {{ date('Y') }} LABOCLYPSA — Todos los derechos reservados
        </p>
    </div>
</div>

</body>
</html>
