<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LABOCLYPSA') — Sistema de Laboratorio</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .label { display:block; font-size:.875rem; font-weight:500; color:#374151; margin-bottom:.25rem; }
        .input { width:100%; border:1px solid #d1d5db; border-radius:.5rem; padding:.5rem .75rem; font-size:.875rem; outline:none; background:#fff; }
        .input:focus { box-shadow:0 0 0 2px #3b82f6; border-color:#3b82f6; }
        .btn-primary { background:#2563eb; color:#fff; font-weight:600; padding:.5rem 1.25rem; border-radius:.5rem; font-size:.875rem; }
        .btn-primary:hover { background:#1d4ed8; }
    </style>
    @stack('head')
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col"
      x-data="{ sidebarOpen: false }">

{{-- NAV SUPERIOR --}}
<nav class="bg-blue-900 text-white shadow-md z-50 relative">
    <div class="max-w-full px-4 flex items-center justify-between h-14">
        <div class="flex items-center gap-3">
            {{-- Hamburger (solo móvil) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden text-blue-200 hover:text-white p-1 rounded focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-9 w-9 rounded-full object-cover bg-white">
                <span class="font-bold text-lg tracking-wide">LABOCLYPSA</span>
            </a>
        </div>

        <div class="flex items-center gap-3">
            {{-- Selector de laboratorio --}}
            @php $labs = \App\Models\Laboratorio::where('activo', true)->get(); @endphp
            @if($labs->count() > 1)
            <form method="POST" class="hidden md:flex items-center gap-2">
                @csrf
                <i class="fas fa-hospital text-blue-300 text-sm"></i>
                <select name="id" onchange="this.form.action='{{ url('laboratorio/seleccionar') }}/'+this.value; this.form.submit()"
                    class="bg-blue-800 border border-blue-600 rounded px-2 py-1 text-sm text-white focus:outline-none">
                    @foreach($labs as $lab)
                        <option value="{{ $lab->id }}" {{ session('laboratorio_activo_id') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>
            @else
                <span class="text-blue-200 text-sm hidden md:block">
                    <i class="fas fa-hospital mr-1"></i>{{ $labs->first()?->nombre ?? '' }}
                </span>
            @endif

            <span class="text-blue-200 text-sm hidden md:block">
                <i class="fas fa-user mr-1"></i>{{ auth()->user()?->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-blue-200 hover:text-white text-sm flex items-center gap-1">
                    <i class="fas fa-sign-out-alt"></i><span class="hidden md:inline ml-1">Salir</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

    {{-- Overlay oscuro (móvil) --}}
    <div x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden">
    </div>

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static top-14 lg:top-auto bottom-0 left-0 z-40
                  w-64 lg:w-56 bg-blue-800 text-white flex-shrink-0 flex flex-col shadow-xl
                  transition-transform duration-300 ease-in-out">
        <nav class="flex-1 py-4 space-y-0.5 px-2 overflow-y-auto">
            <a href="{{ route('ordenes.index') }}"
               @click="sidebarOpen = false"
               class="flex items-center gap-2 px-3 py-2.5 rounded text-sm hover:bg-blue-700 {{ request()->routeIs('ordenes*') || request()->is('/') ? 'bg-blue-700 font-semibold' : '' }}">
                <i class="fas fa-users w-5 text-center"></i> Pacientes
            </a>

            @role('admin')
            <div class="mt-4 border-t border-blue-600 pt-4">
                <p class="px-3 text-xs text-blue-400 uppercase tracking-wider mb-1">Administración</p>
                <a href="{{ route('usuarios.index') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-2 px-3 py-2.5 rounded text-sm hover:bg-blue-700 {{ request()->routeIs('usuarios*') ? 'bg-blue-700 font-semibold' : '' }}">
                    <i class="fas fa-users-cog w-5 text-center"></i> Usuarios
                </a>
                <a href="{{ route('auditoria.index') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-2 px-3 py-2.5 rounded text-sm hover:bg-blue-700 {{ request()->routeIs('auditoria*') ? 'bg-blue-700 font-semibold' : '' }}">
                    <i class="fas fa-history w-5 text-center"></i> Auditoría
                </a>
            </div>
            @endrole
        </nav>
        <div class="p-3 text-xs text-blue-400 border-t border-blue-600">
            <span class="block">{{ auth()->user()?->name }}</span>
            <span class="block">Rol: {{ auth()->user()?->getRoleNames()->first() ?? '—' }}</span>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 overflow-auto p-3 lg:p-6 min-w-0">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded flex items-center gap-2 text-sm">
                <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
