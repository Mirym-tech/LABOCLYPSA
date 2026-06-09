@extends('layouts.app')
@section('title', 'Nueva Orden')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('ordenes.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-2xl font-bold text-gray-800">Nueva Orden de Análisis</h1>
</div>

<form method="POST" action="{{ route('ordenes.store') }}">
@csrf
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Columna izquierda: datos de la orden y paciente --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Datos de la orden --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">Datos de la Orden</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Tipo de Paciente *</label>
                    <select name="tipo_paciente" class="input">
                        <option value="ambulatorio">Ambulatorio</option>
                        <option value="internado">Internado</option>
                    </select>
                </div>
                <div>
                    <label class="label">Fecha de Entrada *</label>
                    <input type="date" name="fecha_entrada" value="{{ date('Y-m-d') }}" class="input" required>
                </div>
                <div>
                    <label class="label">Número de Factura</label>
                    <input type="text" name="numero_factura" class="input" placeholder="FAC-0000">
                </div>
                <div>
                    <label class="label">Laboratorio *</label>
                    <select name="laboratorio_id" class="input" required>
                        @foreach(\App\Models\Laboratorio::where('activo', true)->get() as $lab)
                            <option value="{{ $lab->id }}" {{ session('laboratorio_activo_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="embarazada" id="embarazada" value="1" class="w-4 h-4">
                    <label for="embarazada" class="text-sm text-gray-700">Paciente embarazada</label>
                </div>
            </div>
        </div>

        {{-- Búsqueda / datos del paciente --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">Paciente</h2>

            @if($paciente)
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                    <i class="fas fa-user-circle text-blue-400 text-3xl mt-0.5"></i>
                    <div>
                        <p class="font-semibold text-blue-800">{{ $paciente->nombre }}</p>
                        <p class="text-sm text-blue-600">Cód: {{ $paciente->codigo }} | {{ $paciente->edad }} años | {{ $paciente->sexo == 'F' ? 'Femenino' : 'Masculino' }}</p>
                        <p class="text-sm text-blue-600">Médico: {{ $paciente->medico_tratante ?? '—' }}</p>
                    </div>
                    <a href="{{ route('ordenes.create') }}" class="ml-auto text-xs text-blue-400 hover:text-blue-600 mt-1">Cambiar</a>
                </div>
            @else
                <div class="flex gap-2 mb-3">
                    <input type="text" id="buscar_paciente" placeholder="Buscar paciente por nombre o cédula..."
                        class="input flex-1">
                    <button type="button" onclick="buscarPaciente()"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div id="resultados_paciente" class="space-y-2 max-h-48 overflow-auto"></div>
                <input type="hidden" name="paciente_id" id="paciente_id_hidden">
                <div id="paciente_seleccionado" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2"></div>
                <div class="mt-3">
                    <a href="{{ route('pacientes.create') }}" class="text-sm text-blue-600 hover:underline">
                        <i class="fas fa-plus-circle mr-1"></i> Registrar nuevo paciente
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Columna derecha: selección de análisis --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">
            Análisis a Realizar
        </h2>
        <div class="space-y-4 max-h-[600px] overflow-auto pr-1">
            @foreach($categorias as $categoria => $tipos)
            <div>
                <p class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">{{ $categoria }}</p>
                @foreach($tipos as $tipo)
                <label class="flex items-center gap-2 mb-1 cursor-pointer hover:bg-gray-50 px-2 py-1 rounded">
                    <input type="checkbox" name="analisis_ids[]" value="{{ $tipo->id }}"
                        class="w-4 h-4 text-blue-600 rounded">
                    <span class="text-sm text-gray-700">
                        <span class="font-mono text-blue-600 text-xs">{{ $tipo->codigo }}</span>
                        {{ $tipo->nombre }}
                    </span>
                </label>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
        <i class="fas fa-save"></i> Crear Orden
    </button>
    <a href="{{ route('ordenes.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">
        Cancelar
    </a>
</div>
</form>
@endsection

@push('scripts')
<script>
function buscarPaciente() {
    const q = document.getElementById('buscar_paciente').value;
    if (!q) return;
    fetch(`/pacientes?buscar=${encodeURIComponent(q)}&json=1`)
        .then(r => r.json())
        .then(data => {
            const div = document.getElementById('resultados_paciente');
            div.innerHTML = '';
            data.forEach(p => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-full text-left text-sm border border-gray-200 rounded-lg px-3 py-2 hover:bg-blue-50 hover:border-blue-300';
                btn.innerHTML = `<strong>${p.nombre}</strong> <span class="text-gray-400 text-xs">${p.cedula ?? p.codigo}</span>`;
                btn.onclick = () => seleccionarPaciente(p);
                div.appendChild(btn);
            });
        });
}
function seleccionarPaciente(p) {
    document.getElementById('paciente_id_hidden').value = p.id;
    const div = document.getElementById('paciente_seleccionado');
    div.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i><strong>${p.nombre}</strong> — Cód: ${p.codigo}`;
    div.classList.remove('hidden');
    document.getElementById('resultados_paciente').innerHTML = '';
}
</script>
@endpush
