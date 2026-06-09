@extends('layouts.app')
@section('title', 'Nuevo Paciente')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('pacientes.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-2xl font-bold text-gray-800">Nuevo Paciente</h1>
</div>

<form method="POST" action="{{ route('pacientes.store') }}">
@csrf
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="label">Nombre Completo *</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required class="input" placeholder="Apellido, Nombre">
        </div>
        <div>
            <label class="label">Cédula / Pasaporte</label>
            <input type="text" name="cedula" value="{{ old('cedula') }}" class="input">
        </div>
        <div>
            <label class="label">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono') }}" class="input">
        </div>
        <div class="md:col-span-2">
            <label class="label">Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion') }}" class="input">
        </div>
        <div>
            <label class="label">Edad</label>
            <input type="number" name="edad" value="{{ old('edad') }}" min="0" max="150" class="input">
        </div>
        <div>
            <label class="label">Sexo</label>
            <select name="sexo" class="input">
                <option value="">— Seleccionar —</option>
                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
            </select>
        </div>
        <div>
            <label class="label">Nacionalidad *</label>
            <select name="nacionalidad" class="input" required>
                <option value="dominicana" selected>Dominicana</option>
                <option value="haitiana">Haitiana</option>
                <option value="otra">Otras</option>
            </select>
        </div>
        <div>
            <label class="label">Médico Tratante</label>
            <input type="text" name="medico_tratante" value="{{ old('medico_tratante') }}" class="input">
        </div>
        <div>
            <label class="label">Seguro Médico</label>
            <select name="seguro_medico" class="input">
                <option value="SIN SEGURO" {{ old('seguro_medico') == 'SIN SEGURO' ? 'selected' : '' }}>Sin Seguro</option>
                <optgroup label="── Seguros Privados ──">
                    <option value="ARS HUMANO" {{ old('seguro_medico') == 'ARS HUMANO' ? 'selected' : '' }}>ARS Humano</option>
                    <option value="ARS MAPFRE SALUD" {{ old('seguro_medico') == 'ARS MAPFRE SALUD' ? 'selected' : '' }}>ARS Mapfre Salud</option>
                    <option value="ARS PALIC" {{ old('seguro_medico') == 'ARS PALIC' ? 'selected' : '' }}>ARS Palic</option>
                    <option value="ARS UNIVERSAL" {{ old('seguro_medico') == 'ARS UNIVERSAL' ? 'selected' : '' }}>ARS Universal</option>
                    <option value="ARS FUTURO" {{ old('seguro_medico') == 'ARS FUTURO' ? 'selected' : '' }}>ARS Futuro</option>
                    <option value="ARS META SALUD" {{ old('seguro_medico') == 'ARS META SALUD' ? 'selected' : '' }}>ARS Meta Salud</option>
                    <option value="ARS RESERVAS" {{ old('seguro_medico') == 'ARS RESERVAS' ? 'selected' : '' }}>ARS Reservas (Banreservas)</option>
                    <option value="ARS PLAN SALUD BANCO CENTRAL" {{ old('seguro_medico') == 'ARS PLAN SALUD BANCO CENTRAL' ? 'selected' : '' }}>ARS Plan Salud Banco Central</option>
                    <option value="ARS SIMAG" {{ old('seguro_medico') == 'ARS SIMAG' ? 'selected' : '' }}>ARS Simag</option>
                    <option value="ARS PRIMERA APS" {{ old('seguro_medico') == 'ARS PRIMERA APS' ? 'selected' : '' }}>ARS Primera APS</option>
                    <option value="ARS RENACER" {{ old('seguro_medico') == 'ARS RENACER' ? 'selected' : '' }}>ARS Renacer</option>
                    <option value="ARS BMI" {{ old('seguro_medico') == 'ARS BMI' ? 'selected' : '' }}>ARS BMI</option>
                    <option value="ARS SALUD SEGURA" {{ old('seguro_medico') == 'ARS SALUD SEGURA' ? 'selected' : '' }}>ARS Salud Segura</option>
                    <option value="ARS MONUMENTAL" {{ old('seguro_medico') == 'ARS MONUMENTAL' ? 'selected' : '' }}>ARS Monumental</option>
                </optgroup>
                <optgroup label="── Seguros Públicos ──">
                    <option value="SENASA CONTRIBUTIVO" {{ old('seguro_medico') == 'SENASA CONTRIBUTIVO' ? 'selected' : '' }}>SeNaSa Contributivo</option>
                    <option value="SENASA SUBSIDIADO" {{ old('seguro_medico') == 'SENASA SUBSIDIADO' ? 'selected' : '' }}>SeNaSa Subsidiado</option>
                    <option value="SENASA CONTRIBUTIVO SUBSIDIADO" {{ old('seguro_medico') == 'SENASA CONTRIBUTIVO SUBSIDIADO' ? 'selected' : '' }}>SeNaSa Contrib. Subsidiado</option>
                    <option value="IDSS / TSS" {{ old('seguro_medico') == 'IDSS / TSS' ? 'selected' : '' }}>IDSS / TSS</option>
                    <option value="FFAA Y POLICIA" {{ old('seguro_medico') == 'FFAA Y POLICIA' ? 'selected' : '' }}>FFAA y Policía Nacional</option>
                </optgroup>
            </select>
        </div>
        <div>
            <label class="label">Cuenta #</label>
            <input type="text" name="cuenta" value="{{ old('cuenta') }}" class="input">
        </div>
        <div>
            <label class="label">Laboratorio *</label>
            <select name="laboratorio_id" class="input" required>
                @foreach($laboratorios as $lab)
                    <option value="{{ $lab->id }}" {{ session('laboratorio_activo_id') == $lab->id ? 'selected' : '' }}>
                        {{ $lab->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-6 flex gap-3 border-t pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
            <i class="fas fa-save"></i> Registrar Paciente
        </button>
        <a href="{{ route('pacientes.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancelar</a>
    </div>
</div>
</form>
@endsection
