@extends('layouts.app')
@section('title', 'Editar Paciente')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('pacientes.show', $paciente) }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-2xl font-bold text-gray-800">Editar: {{ $paciente->nombre }}</h1>
</div>

<form method="POST" action="{{ route('pacientes.update', $paciente) }}">
@csrf @method('PUT')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="label">Nombre Completo *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required class="input">
        </div>
        <div>
            <label class="label">Cédula / Pasaporte</label>
            <input type="text" name="cedula" value="{{ old('cedula', $paciente->cedula) }}" class="input">
        </div>
        <div>
            <label class="label">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $paciente->telefono) }}" class="input">
        </div>
        <div class="md:col-span-2">
            <label class="label">Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion', $paciente->direccion) }}" class="input">
        </div>
        <div>
            <label class="label">Edad</label>
            <input type="number" name="edad" value="{{ old('edad', $paciente->edad) }}" min="0" max="150" class="input">
        </div>
        <div>
            <label class="label">Sexo</label>
            <select name="sexo" class="input">
                <option value="">— Seleccionar —</option>
                <option value="F" {{ old('sexo', $paciente->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                <option value="M" {{ old('sexo', $paciente->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
            </select>
        </div>
        <div>
            <label class="label">Nacionalidad *</label>
            <select name="nacionalidad" class="input" required>
                @foreach(['dominicana','haitiana','otra'] as $n)
                <option value="{{ $n }}" {{ old('nacionalidad', $paciente->nacionalidad) == $n ? 'selected' : '' }}>{{ ucfirst($n) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Médico Tratante</label>
            <input type="text" name="medico_tratante" value="{{ old('medico_tratante', $paciente->medico_tratante) }}" class="input">
        </div>
        <div>
            <label class="label">Seguro Médico</label>
            @php $seguro = old('seguro_medico', $paciente->seguro_medico ?? 'SIN SEGURO'); @endphp
            <select name="seguro_medico" class="input">
                <option value="SIN SEGURO" {{ $seguro == 'SIN SEGURO' ? 'selected' : '' }}>Sin Seguro</option>
                <optgroup label="── Seguros Privados ──">
                    @foreach(['ARS HUMANO','ARS MAPFRE SALUD','ARS PALIC','ARS UNIVERSAL','ARS FUTURO','ARS META SALUD','ARS RESERVAS','ARS PLAN SALUD BANCO CENTRAL','ARS SIMAG','ARS PRIMERA APS','ARS RENACER','ARS BMI','ARS SALUD SEGURA','ARS MONUMENTAL'] as $s)
                    <option value="{{ $s }}" {{ $seguro == $s ? 'selected' : '' }}>{{ ucwords(strtolower($s)) }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="── Seguros Públicos ──">
                    @foreach(['SENASA CONTRIBUTIVO','SENASA SUBSIDIADO','SENASA CONTRIBUTIVO SUBSIDIADO','IDSS / TSS','FFAA Y POLICIA'] as $s)
                    <option value="{{ $s }}" {{ $seguro == $s ? 'selected' : '' }}>{{ ucwords(strtolower($s)) }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div>
            <label class="label">Cuenta #</label>
            <input type="text" name="cuenta" value="{{ old('cuenta', $paciente->cuenta) }}" class="input">
        </div>
    </div>
    <div class="mt-6 flex gap-3 border-t pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
            <i class="fas fa-save"></i> Actualizar
        </button>
        <a href="{{ route('pacientes.show', $paciente) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancelar</a>
    </div>
</div>
</form>
@endsection
