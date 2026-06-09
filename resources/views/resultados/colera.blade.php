@extends('layouts.app')
@section('title', 'Análisis de Cólera — ' . $oa->orden->numero_orden)

@section('content')
@php $r = $resultado; @endphp
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-xl font-bold text-gray-800">Análisis de Cólera — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
    <a href="{{ route('pdf.colera', $oa) }}" target="_blank" class="ml-auto bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fas fa-print mr-1"></i> Imprimir Resultado
    </a>
</div>

<form method="POST" action="{{ route('resultados.colera.guardar', $oa) }}">
@csrf
<div class="bg-white rounded-xl shadow-sm p-6 space-y-5 max-w-2xl">
    <div>
        <label class="label">Bioanalista</label>
        <select name="bioanalista_id" class="input w-56">
            <option value="">— Seleccionar —</option>
            @foreach($bioanalistas as $bio)
                <option value="{{ $bio->id }}" {{ ($r->bioanalista_id ?? '') == $bio->id ? 'selected' : '' }}>{{ $bio->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="label">Color</label>
            <input type="text" name="color" value="{{ $r->color ?? 'AMARILLO' }}" class="input">
        </div>
        <div>
            <label class="label">Consistencia</label>
            <input type="text" name="consistencia" value="{{ $r->consistencia ?? 'DIARREICA' }}" class="input">
        </div>
    </div>

    <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1">Vibrio Cholerae</h3>
    <div class="space-y-3">
        <div class="flex items-center gap-4">
            <label class="w-52 text-sm font-medium text-gray-700">Vibrio Cholerae (VC0-1)</label>
            <input type="text" name="vc01" value="{{ $r->vc01 ?? '' }}" class="input flex-1">
        </div>
        <div class="flex items-center gap-4">
            <label class="w-52 text-sm font-medium text-gray-700">Vibrio Cholerae (VC01-1)</label>
            <input type="text" name="vc01_1" value="{{ $r->vc01_1 ?? '' }}" class="input flex-1">
        </div>
        <div class="flex items-center gap-4">
            <label class="w-52 text-sm font-medium text-gray-700">Vibrio Cholerae (VC0-139)</label>
            <input type="text" name="vc0139" value="{{ $r->vc0139 ?? '' }}" class="input flex-1">
        </div>
    </div>

    <div>
        <label class="label">Observación</label>
        <textarea name="observacion" rows="3" class="input w-full">{{ $r->observacion ?? '' }}</textarea>
    </div>

    <div class="flex gap-3 border-t pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
            <i class="fas fa-save"></i> Grabar
        </button>
        <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium">Cancelar</a>
    </div>
</div>
</form>
@endsection
