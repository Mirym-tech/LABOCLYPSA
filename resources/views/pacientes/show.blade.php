@extends('layouts.app')
@section('title', $paciente->nombre)

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('pacientes.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-2xl font-bold text-gray-800">{{ $paciente->nombre }}</h1>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('pacientes.edit', $paciente) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('ordenes.create', ['paciente_id' => $paciente->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Orden
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-2">Datos del Paciente</h2>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div><dt class="text-gray-400">Código</dt><dd class="font-mono font-semibold text-blue-600">{{ $paciente->codigo }}</dd></div>
            <div><dt class="text-gray-400">Cédula</dt><dd>{{ $paciente->cedula ?? '—' }}</dd></div>
            <div><dt class="text-gray-400">Edad</dt><dd>{{ $paciente->edad ?? '—' }} años</dd></div>
            <div><dt class="text-gray-400">Sexo</dt><dd>{{ $paciente->sexo == 'F' ? 'Femenino' : ($paciente->sexo == 'M' ? 'Masculino' : '—') }}</dd></div>
            <div><dt class="text-gray-400">Teléfono</dt><dd>{{ $paciente->telefono ?? '—' }}</dd></div>
            <div><dt class="text-gray-400">Nacionalidad</dt><dd class="capitalize">{{ $paciente->nacionalidad }}</dd></div>
            <div class="col-span-2"><dt class="text-gray-400">Dirección</dt><dd>{{ $paciente->direccion ?? '—' }}</dd></div>
            <div><dt class="text-gray-400">Médico</dt><dd>{{ $paciente->medico_tratante ?? '—' }}</dd></div>
            <div><dt class="text-gray-400">Seguro</dt><dd>{{ $paciente->seguro_medico ?? '—' }}</dd></div>
            <div><dt class="text-gray-400">Cuenta</dt><dd>{{ $paciente->cuenta ?? '—' }}</dd></div>
            <div><dt class="text-gray-400">Laboratorio</dt><dd>{{ $paciente->laboratorio->nombre }}</dd></div>
        </dl>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-2">Estadísticas</h2>
        <div class="text-center py-4">
            <div class="text-4xl font-bold text-blue-600">{{ $paciente->ordenes->count() }}</div>
            <div class="text-gray-500 text-sm">Órdenes totales</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b bg-gray-50">
        <h2 class="font-semibold text-gray-700"><i class="fas fa-history text-blue-500 mr-2"></i>Historial de Órdenes</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-blue-50">
            <tr>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Nº Orden</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Fecha</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Análisis</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($paciente->ordenes->sortByDesc('created_at') as $orden)
            @php $colores = ['pendiente'=>'yellow','en_proceso'=>'blue','listo'=>'green','por_validar'=>'orange','validado'=>'gray']; $c = $colores[$orden->estado] ?? 'gray'; @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-mono text-blue-600">{{ $orden->numero_orden }}</td>
                <td class="px-4 py-2.5 text-gray-600">{{ $orden->fecha_entrada?->format('d/m/Y') }}</td>
                <td class="px-4 py-2.5">
                    @foreach($orden->analisis->take(3) as $a)
                        <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded mr-1">{{ $a->tipo->codigo }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-2.5">
                    <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $c }}-100 text-{{ $c }}-800">{{ ucfirst(str_replace('_',' ',$orden->estado)) }}</span>
                </td>
                <td class="px-4 py-2.5 text-right">
                    <a href="{{ route('ordenes.show', $orden) }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver <i class="fas fa-arrow-right ml-1"></i></a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Sin órdenes registradas</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
