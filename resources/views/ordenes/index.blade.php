@extends('layouts.app')
@section('title', 'Pacientes')

@section('content')

<div x-data="{
    modal: false,
    selected: null,
    seleccionar(p) { this.selected = p; this.modal = true; }
}">

{{-- Encabezado --}}
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users text-blue-600 mr-2"></i>Pacientes</h1>
    <a href="{{ route('pacientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Nuevo Paciente
    </a>
</div>

{{-- Buscador --}}
<form method="GET" class="bg-white p-4 rounded-xl shadow-sm mb-6 flex gap-3">
    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, cédula, código u orden..."
        class="input flex-1">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        <i class="fas fa-search"></i>
    </button>
    @if(request('buscar'))
    <a href="{{ route('ordenes.index') }}" class="text-gray-500 px-3 py-2 rounded-lg hover:bg-gray-100"><i class="fas fa-times"></i></a>
    @endif
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col" style="height: calc(100vh - 16rem);">
    <div class="flex-1 overflow-auto">
        <table class="w-full text-xs border-collapse">
            <thead class="sticky top-0 z-10">
                <tr class="bg-gray-700 text-white">
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-20">Código</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600">Nombre</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-24">Cédula</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-16">Edad/Sexo</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-28">Médico</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-24">Seguro</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-20">Última Orden</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-22">Fecha</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-24">Laboratorio</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-28">Dirección</th>
                    <th class="px-2 py-2 text-left font-semibold border-r border-gray-600 w-24">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacientes as $paciente)
                @php $orden = $paciente->ordenes->first(); @endphp
                <tr class="cursor-pointer border-b border-gray-100 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-100"
                    @click="seleccionar({
                        id: {{ $paciente->id }},
                        nombre: '{{ addslashes(strtoupper($paciente->nombre)) }}',
                        ordenId: {{ $orden?->id ?? 'null' }},
                        numero: '{{ $orden?->numero_orden ?? '' }}',
                        urlAbrir: '{{ $orden ? route('ordenes.show', $orden) : '#' }}',
                        urlEditar: '{{ route('pacientes.edit', $paciente) }}',
                        urlNuevaOrden: '{{ route('ordenes.create', ['paciente_id' => $paciente->id]) }}'
                    })">
                    <td class="px-2 py-1.5 border-r border-gray-200 font-mono text-blue-600">{{ $paciente->codigo }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 font-medium text-gray-800 uppercase">{{ strtoupper($paciente->nombre) }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-500">{{ $paciente->cedula ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-500 text-center">{{ $paciente->edad ?? '—' }}{{ $paciente->edad ? 'a' : '' }} / {{ $paciente->sexo ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-500 truncate">{{ $paciente->medico_tratante ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-500 truncate">{{ $paciente->seguro_medico ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 font-mono text-blue-700">{{ $orden?->numero_orden ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-600">{{ $orden?->fecha_entrada?->format('d-m-Y') ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-400">{{ $paciente->laboratorio?->nombre ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-400 truncate">{{ $paciente->direccion ?? '—' }}</td>
                    <td class="px-2 py-1.5 border-r border-gray-200 text-gray-400">{{ $paciente->telefono ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-4 py-16 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3 block"></i>No hay pacientes registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pacientes->hasPages())
    <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 text-xs text-gray-500 flex items-center justify-between">
        <span>{{ $pacientes->total() }} pacientes</span>
        {{ $pacientes->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- Modal de opciones --}}
<div x-show="modal" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
    @keydown.escape.window="modal = false">
    <div class="absolute inset-0 bg-black bg-opacity-40" @click="modal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-80 overflow-hidden z-10">
        <div class="bg-blue-700 text-white px-5 py-4">
            <p class="text-xs text-blue-200 uppercase tracking-wider mb-0.5">Paciente seleccionado</p>
            <p class="font-bold text-base leading-tight" x-text="selected?.nombre"></p>
            <p class="text-blue-200 text-xs mt-0.5" x-show="selected?.numero">Orden # <span x-text="selected?.numero"></span></p>
        </div>
        <div class="p-4 space-y-2">
            <template x-if="selected?.ordenId">
                <a :href="selected?.urlAbrir"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium text-sm transition">
                    <i class="fas fa-folder-open w-5 text-center text-blue-500"></i>
                    Abrir / Ver resultados
                </a>
            </template>

            <a :href="selected?.urlNuevaOrden"
                class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium text-sm transition">
                <i class="fas fa-plus-circle w-5 text-center text-purple-500"></i>
                Nueva orden para este paciente
            </a>

            <a :href="selected?.urlEditar"
                class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-yellow-50 hover:bg-yellow-100 text-yellow-700 font-medium text-sm transition">
                <i class="fas fa-user-edit w-5 text-center text-yellow-500"></i>
                Editar datos del paciente
            </a>

            <button @click="
                if(confirm('¿Eliminar este paciente y todas sus órdenes?')) {
                    document.getElementById('form-borrar-' + selected.id).submit();
                }"
                class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-medium text-sm transition">
                <i class="fas fa-trash w-5 text-center text-red-400"></i>
                Borrar paciente
            </button>
        </div>
        <div class="px-4 pb-4">
            <button @click="modal = false"
                class="w-full py-2 text-xs text-gray-400 hover:text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                Cancelar
            </button>
        </div>
    </div>
</div>

{{-- Forms ocultos para borrar --}}
@foreach($pacientes as $paciente)
<form id="form-borrar-{{ $paciente->id }}" method="POST" action="{{ route('pacientes.destroy', $paciente->id) }}" class="hidden">
    @csrf @method('DELETE')
</form>
@endforeach

</div>

@push('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
