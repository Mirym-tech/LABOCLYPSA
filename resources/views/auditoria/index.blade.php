@extends('layouts.app')
@section('title', 'Auditoría')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-history text-blue-600 mr-2"></i>Registro de Auditoría</h1>
</div>

<form method="GET" class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-wrap gap-3">
    <select name="log" class="input w-40">
        <option value="">Todos los módulos</option>
        @foreach($logs as $l)
            <option value="{{ $l }}" {{ request('log') == $l ? 'selected' : '' }}>{{ ucfirst($l) }}</option>
        @endforeach
    </select>
    <select name="usuario_id" class="input w-48">
        <option value="">Todos los usuarios</option>
        @foreach($usuarios as $u)
            <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
        @endforeach
    </select>
    <input type="date" name="desde" value="{{ request('desde') }}" class="input w-36" placeholder="Desde">
    <input type="date" name="hasta" value="{{ request('hasta') }}" class="input w-36" placeholder="Hasta">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        <i class="fas fa-filter"></i> Filtrar
    </button>
    <a href="{{ route('auditoria.index') }}" class="text-gray-500 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">Limpiar</a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-blue-50">
            <tr>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Fecha/Hora</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Usuario</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Módulo</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Acción</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Cambios</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($actividades as $act)
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-2.5 text-gray-600 text-xs whitespace-nowrap">{{ $act->created_at?->format('d/m/Y H:i:s') }}</td>
            <td class="px-4 py-2.5">
                <span class="font-medium">{{ $act->causer?->name ?? 'Sistema' }}</span>
            </td>
            <td class="px-4 py-2.5">
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded">{{ $act->log_name }}</span>
            </td>
            <td class="px-4 py-2.5 text-gray-700">{{ $act->description }}</td>
            <td class="px-4 py-2.5 text-xs text-gray-400 max-w-xs truncate">
                @if($act->properties->count())
                    {{ $act->properties->toJson() }}
                @else —
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-history text-3xl mb-3 block"></i>Sin registros de auditoría</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($actividades->hasPages())<div class="px-4 py-3 border-t">{{ $actividades->links() }}</div>@endif
</div>
@endsection
