@extends('layouts.app')
@section('title', 'Digestión en Heces — ' . $oa->orden->numero_orden)

@section('content')
@php $r = $resultado; @endphp
<div class="flex flex-wrap items-start gap-2 mb-5">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600 mt-1 flex-shrink-0"><i class="fas fa-arrow-left"></i></a>
    <h1 class="text-lg sm:text-xl font-bold text-gray-800 leading-tight min-w-0 flex-1">Digestión en Heces — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
    <a href="{{ route('pdf.digestion', $oa) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm flex-shrink-0"><i class="fas fa-print mr-1"></i>Imprimir</a>
</div>

<form method="POST" action="{{ route('resultados.digestion.guardar', $oa) }}">
@csrf
<div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
    <div>
        <label class="label">Bioanalista</label>
        <select name="bioanalista_id" class="input w-56">
            <option value="">— Seleccionar —</option>
            @foreach($bioanalistas as $bio)
                <option value="{{ $bio->id }}" {{ ($r->bioanalista_id ?? '') == $bio->id ? 'selected' : '' }}>{{ $bio->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">Físico-Químico</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @foreach(['color'=>'Color','olor'=>'Olor','consistencia'=>'Consistencia','alimentos_no_digeridos'=>'Alimentos No Digeridos','mucus'=>'Mucus','reaccion_ph'=>'Reacción (pH)','sangre_oculta'=>'Sangre Oculta','grasas'=>'Grasas','sustancia_reductora'=>'Sustancia Reductora','tripsina'=>'Tripsina'] as $c=>$e)
            <div>
                <label class="label text-xs">{{ $e }}</label>
                <input type="text" name="{{ $c }}" value="{{ $r->$c ?? '' }}" class="input">
            </div>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">Examen Microscópico</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(['leucocitos'=>'Leucocitos','eritrocitos'=>'Eritrocitos','celulas_epiteliales'=>'Células Epiteliales','fibras_mucosas'=>'Fibras Mucosas','cristales'=>'Cristales','bacterias'=>'Bacterias','huevos'=>'Huevos','parasitos'=>'Parásitos','quistes'=>'Quistes','granulos'=>'Gránulos','larvas'=>'Larvas','materiales_extranos'=>'Materiales Extraños'] as $c=>$e)
            <div>
                <label class="label text-xs">{{ $e }}</label>
                <input type="text" name="{{ $c }}" value="{{ $r->$c ?? '' }}" class="input">
            </div>
            @endforeach
        </div>
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
