@extends('layouts.app')
@section('title', 'Serología — ' . $oa->orden->numero_orden)

@section('content')
@php $r = $resultado; $paciente = $oa->orden->paciente; @endphp

<div class="flex flex-wrap items-start gap-2 mb-5">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600 mt-1 flex-shrink-0"><i class="fas fa-arrow-left"></i></a>
    <div class="min-w-0 flex-1">
        <h1 class="text-lg sm:text-xl font-bold text-gray-800 leading-tight">Serología / Aglutininas Febriles — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
        <p class="text-sm text-gray-500">{{ $paciente->nombre }}</p>
    </div>
</div>

<form method="POST" action="{{ route('resultados.serologia.guardar', $oa) }}">
@csrf
<div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
    <div class="flex gap-4 items-end">
        <div>
            <label class="label">Bioanalista</label>
            <select name="bioanalista_id" class="input w-56">
                <option value="">— Seleccionar —</option>
                @foreach($bioanalistas as $bio)
                    <option value="{{ $bio->id }}" {{ ($r->bioanalista_id ?? '') == $bio->id ? 'selected' : '' }}>{{ $bio->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="reportar" id="reportar" value="1" {{ ($r->reportar ?? false) ? 'checked' : '' }} class="w-4 h-4">
            <label for="reportar" class="text-sm font-medium text-gray-700">Reportar</label>
        </div>
    </div>

    <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1">Resultados</h3>

    @php
    $parametros = [
        'salmonella_o_a' => 'Salmonella O Grupo A',
        'salmonella_o_b' => 'Salmonella O Grupo B',
        'salmonella_o_c' => 'Salmonella O Grupo C',
        'salmonella_o_d' => 'Salmonella O Grupo D',
        'salmonella_h_a' => 'Salmonella H Grupo A',
        'salmonella_h_b' => 'Salmonella H Grupo B',
        'salmonella_h_c' => 'Salmonella H Grupo C',
        'salmonella_h_d' => 'Salmonella H Grupo D',
        'proteus_ox2'   => 'Proteus OX 2',
        'proteus_ox19'  => 'Proteus OX 19',
        'proteus_oxk'   => 'Proteus OX K',
        'brucella_abortus'     => 'Brucella Abortus',
        'typhoide_o_somatica'  => 'Typhoide O Somática',
    ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($parametros as $campo => $etiqueta)
        <div class="flex items-center gap-3">
            <label class="w-56 text-sm text-gray-700 font-medium flex-shrink-0">{{ $etiqueta }}</label>
            <input type="text" name="{{ $campo }}" value="{{ $r->$campo ?? 'NEGATIVO' }}"
                class="input flex-1" placeholder="NEGATIVO">
        </div>
        @endforeach
    </div>

    <div>
        <label class="label">Observación</label>
        <textarea name="observacion" rows="3" class="input w-full">{{ $r->observacion ?? '' }}</textarea>
    </div>

    <div class="sticky bottom-0 z-10 bg-white border-t border-gray-200 shadow-md
                -mx-3 lg:-mx-6 px-3 lg:px-6 py-3 mt-2 flex gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-900 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
            <i class="fas fa-save"></i> Grabar
        </button>
        <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium">Cancelar</a>
    </div>
</div>
</form>
@endsection
