@extends('layouts.app')
@section('title', 'Pacientes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users text-blue-600 mr-2"></i>Pacientes</h1>
    <a href="{{ route('pacientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Nuevo Paciente
    </a>
</div>

<form method="GET" class="bg-white p-4 rounded-xl shadow-sm mb-6 flex gap-3">
    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, cédula o código..."
        class="input flex-1">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        <i class="fas fa-search"></i>
    </button>
    @if(request('buscar'))
    <a href="{{ route('pacientes.index') }}" class="text-gray-500 px-3 py-2 rounded-lg hover:bg-gray-100"><i class="fas fa-times"></i></a>
    @endif
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-blue-50 border-b border-blue-100">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-blue-800">Código</th>
                <th class="px-4 py-3 text-left font-semibold text-blue-800">Nombre</th>
                <th class="px-4 py-3 text-left font-semibold text-blue-800">Cédula</th>
                <th class="px-4 py-3 text-left font-semibold text-blue-800">Edad/Sexo</th>
                <th class="px-4 py-3 text-left font-semibold text-blue-800">Médico</th>
                <th class="px-4 py-3 text-left font-semibold text-blue-800">Laboratorio</th>
                <th class="px-4 py-3 text-center font-semibold text-blue-800">Nueva Orden</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pacientes as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-mono text-blue-600 text-xs">{{ $p->codigo }}</td>
                <td class="px-4 py-2.5">
                    <a href="{{ route('pacientes.edit', $p) }}" class="font-semibold text-blue-700 hover:underline">{{ $p->nombre }}</a>
                </td>
                <td class="px-4 py-2.5 text-gray-600">{{ $p->cedula ?? '—' }}</td>
                <td class="px-4 py-2.5 text-gray-600">{{ $p->edad ?? '—' }}a / {{ $p->sexo == 'F' ? 'F' : ($p->sexo == 'M' ? 'M' : '—') }}</td>
                <td class="px-4 py-2.5 text-gray-600 text-xs">{{ $p->medico_tratante ?? '—' }}</td>
                <td class="px-4 py-2.5 text-xs text-gray-400">{{ $p->laboratorio->nombre }}</td>
                <td class="px-4 py-2.5 text-center">
                    <a href="{{ route('ordenes.create', ['paciente_id' => $p->id]) }}"
                        class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg">
                        <i class="fas fa-plus"></i> Nueva Orden
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-user-slash text-4xl mb-3 block"></i>No hay pacientes registrados</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($pacientes->hasPages())<div class="px-4 py-3 border-t">{{ $pacientes->links() }}</div>@endif
</div>
@endsection
