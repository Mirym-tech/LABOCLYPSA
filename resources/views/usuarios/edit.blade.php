@extends('layouts.app')
@section('title', 'Editar Usuario')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('usuarios.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-2xl font-bold text-gray-800">Editar: {{ $usuario->name }}</h1>
</div>
<form method="POST" action="{{ route('usuarios.update', $usuario) }}">
@csrf @method('PUT')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <div class="space-y-4">
        <div><label class="label">Nombre *</label><input type="text" name="name" value="{{ old('name', $usuario->name) }}" required class="input"></div>
        <div><label class="label">Email *</label><input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="input"></div>
        <div>
            <label class="label">Nueva Contraseña <span class="text-gray-400 font-normal">(dejar en blanco para no cambiar)</span></label>
            <input type="password" name="password" minlength="8" class="input">
        </div>
        <div><label class="label">Confirmar Nueva Contraseña</label><input type="password" name="password_confirmation" class="input"></div>
        <div>
            <label class="label">Rol *</label>
            <select name="role" class="input" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>{{ ucfirst($rol->name) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Laboratorio *</label>
            <select name="laboratorio_id" class="input" required>
                @foreach($laboratorios as $lab)
                    <option value="{{ $lab->id }}" {{ $usuario->laboratorio_id == $lab->id ? 'selected' : '' }}>{{ $lab->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="activo" id="activo" value="1" {{ $usuario->activo ? 'checked' : '' }} class="w-4 h-4">
            <label for="activo" class="text-sm font-medium text-gray-700">Usuario activo</label>
        </div>
    </div>
    <div class="mt-6 flex gap-3 border-t pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2"><i class="fas fa-save"></i> Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium">Cancelar</a>
    </div>
</div>
</form>
@endsection
