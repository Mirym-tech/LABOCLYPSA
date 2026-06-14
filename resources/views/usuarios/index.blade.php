@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users-cog text-blue-600 mr-2"></i>Usuarios del Sistema</h1>
    <a href="{{ route('usuarios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-blue-50">
            <tr>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Nombre</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Email</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Rol</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Laboratorio</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @foreach($usuarios as $u)
        <tr class="hover:bg-gray-50 {{ $u->trashed() ? 'opacity-50' : '' }}">
            <td class="px-4 py-2.5 font-medium {{ $u->trashed() ? 'line-through text-gray-400' : '' }}">{{ $u->name }}</td>
            <td class="px-4 py-2.5 text-gray-600">{{ $u->email }}</td>
            <td class="px-4 py-2.5">
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ $u->getRoleNames()->first() ?? '—' }}</span>
            </td>
            <td class="px-4 py-2.5 text-gray-600 text-xs">{{ $u->laboratorio?->nombre ?? '—' }}</td>
            <td class="px-4 py-2.5">
                @if($u->trashed())
                    <span class="text-gray-400 text-xs font-medium"><i class="fas fa-trash mr-1"></i>Eliminado</span>
                @elseif($u->activo)
                    <span class="text-green-600 text-xs font-medium"><i class="fas fa-circle mr-1"></i>Activo</span>
                @else
                    <span class="text-red-400 text-xs font-medium"><i class="fas fa-circle mr-1"></i>Inactivo</span>
                @endif
            </td>
            <td class="px-4 py-2.5 text-right">
                @if($u->trashed())
                    {{-- Usuario eliminado: solo mostrar botón de restaurar --}}
                    <form method="POST" action="{{ route('usuarios.restore', $u->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm" title="Restaurar usuario">
                            <i class="fas fa-undo mr-1"></i>Restaurar
                        </button>
                    </form>
                @else
                    {{-- Usuario activo: editar y eliminar --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('usuarios.edit', $u) }}" class="text-blue-600 hover:text-blue-800 text-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('usuarios.destroy', $u) }}"
                              onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($u->name) }}? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @if($usuarios->hasPages())<div class="px-4 py-3 border-t">{{ $usuarios->links() }}</div>@endif
</div>
@endsection
