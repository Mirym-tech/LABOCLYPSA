@extends('layouts.app')
@section('title', 'Uroanálisis — ' . $oa->orden->numero_orden)

@section('content')
@php $paciente = $oa->orden->paciente; @endphp

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h1 class="text-xl font-bold text-gray-800">Uroanálisis / Coprológico — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
        <p class="text-sm text-gray-500">{{ $paciente->nombre }}</p>
    </div>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('pdf.uroanalisis', $oa) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm"><i class="fas fa-print mr-1"></i>Imprimir Orina</a>
        <a href="{{ route('pdf.coprologia', $oa) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm"><i class="fas fa-print mr-1"></i>Imprimir Coprológico</a>
    </div>
</div>

<div x-data="{ tab: 'uro' }">
<div class="flex gap-1 mb-4 bg-gray-200 p-1 rounded-lg w-fit">
    <button @click="tab='uro'" :class="tab==='uro' ? 'bg-white shadow text-blue-700':'text-gray-600'" class="px-5 py-2 rounded-md text-sm font-medium">Uroanálisis</button>
    <button @click="tab='cop'" :class="tab==='cop' ? 'bg-white shadow text-blue-700':'text-gray-600'" class="px-5 py-2 rounded-md text-sm font-medium">Coprológico</button>
</div>

<form method="POST" action="{{ route('resultados.uroanalisis.guardar', $oa) }}">
@csrf

{{-- Bioanalista común --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-4">
    <label class="label">Bioanalista</label>
    <select name="uro[bioanalista_id]" class="input w-56">
        <option value="">— Seleccionar —</option>
        @foreach($bioanalistas as $bio)
            <option value="{{ $bio->id }}" {{ ($uro->bioanalista_id ?? '') == $bio->id ? 'selected' : '' }}>{{ $bio->name }}</option>
        @endforeach
    </select>
</div>

{{-- Tab Uroanálisis --}}
<div x-show="tab==='uro'">
<div class="bg-white rounded-xl shadow-sm p-5 space-y-5">
    @php $u = $uro; @endphp

    <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1">Físico-Químico</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div>
            <label class="label text-xs">Color</label>
            <input type="text" name="uro[color]" value="{{ $u->color ?? '' }}" class="input">
        </div>
        <div>
            <label class="label text-xs">Aspecto</label>
            <select name="uro[aspecto]" class="input">
                @foreach(['','LIGERO TURBIO','TURBIO','LIGERO','CLARO'] as $opt)
                    <option value="{{ $opt }}" {{ ($u->aspecto ?? '') == $opt ? 'selected' : '' }}>{{ $opt ?: '— Seleccionar —' }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="label text-xs">Densidad</label><input type="text" name="uro[densidad]" value="{{ $u->densidad ?? '' }}" class="input"></div>
        <div><label class="label text-xs">pH</label><input type="text" name="uro[ph]" value="{{ $u->ph ?? '' }}" class="input"></div>
        @php $dropOpts = [''=>'— Seleccionar —','NEGATIVO'=>'NEGATIVO','POSITIVO 1(+)'=>'POSITIVO 1(+)','POSITIVO 2(+)'=>'POSITIVO 2(+)','POSITIVO 3(+)'=>'POSITIVO 3(+)','POSITIVO 4(+)'=>'POSITIVO 4(+)','TRAZAS'=>'TRAZAS']; @endphp
        @foreach(['glucosa'=>'Glucosa','proteina'=>'Proteína','acetona'=>'Acetona','bilirrubina'=>'Bilirrubina','urobilinogeno'=>'Urobilinógeno','sangre_oculta'=>'Sangre Oculta','hemoglobina'=>'Hemoglobina'] as $campo => $etiq)
        <div>
            <label class="label text-xs">{{ $etiq }}</label>
            <select name="uro[{{ $campo }}]" class="input">
                @foreach($dropOpts as $v=>$l)<option value="{{ $v }}" {{ ($u->$campo ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
        </div>
        @endforeach
        <div><label class="label text-xs">Nitrito</label><input type="text" name="uro[nitrito]" value="{{ $u->nitrito ?? '' }}" class="input"></div>
    </div>

    <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1">Segmento Urinario</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div><label class="label text-xs">Leucocitos</label><input type="text" name="uro[leucocitos]" value="{{ $u->leucocitos ?? '0-2 /C' }}" class="input"></div>
        <div><label class="label text-xs">Eritrocitos</label><input type="text" name="uro[eritrocitos]" value="{{ $u->eritrocitos ?? '0-1 /C' }}" class="input"></div>
        @php $presOpts = [''=>'— Sel —','AUSENTES'=>'AUSENTES','ALGUNAS'=>'ALGUNAS','ESCASAS'=>'ESCASAS','ABUNDANTES'=>'ABUNDANTES','MODERADAS'=>'MODERADAS','NUMEROSAS'=>'NUMEROSAS']; @endphp
        @foreach(['celulas_epiteliales'=>'Células Epiteliales','celulas_renales'=>'Células Renales'] as $c=>$e)
        <div><label class="label text-xs">{{ $e }}</label>
        <select name="uro[{{ $c }}]" class="input">@foreach($presOpts as $v=>$l)<option value="{{ $v }}" {{ ($u->$c ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
        @endforeach
        @php
        $boolOpts = [''=>'— Sel —','AUSENTES'=>'AUSENTES','PRESENTES'=>'PRESENTES'];
        $boolFields = ['bacterias'=>'Bacterias','fibras_mucosas'=>'Fibras Mucosas','levaduras'=>'Levaduras','t_vaginalis'=>'T. Vaginalis'];
        $cristalesOpts = [''=>'— Sel —','AUSENTES'=>'AUSENTES','URATOS AMORFOS'=>'URATOS AMORFOS','ACIDO URICO'=>'ÁCIDO ÚRICO','OXALATO CALCIO'=>'OXALATO CALCIO','FOSFATO'=>'FOSFATO','FOSFATO TRIPLE'=>'FOSFATO TRIPLE'];
        $cilindrosOpts = [''=>'— Sel —','AUSENTES'=>'AUSENTES','GRANULOSOS'=>'GRANULOSOS','HIALINOS'=>'HIALINOS','EPITELIALES'=>'EPITELIALES','GLOBULOS ROJOS'=>'GLÓBULOS ROJOS','GLOBULOS BLANCO'=>'GLÓBULOS BLANCOS'];
        @endphp
        @foreach($boolFields as $c=>$e)
        <div><label class="label text-xs">{{ $e }}</label>
        <select name="uro[{{ $c }}]" class="input">@foreach($boolOpts as $v=>$l)<option value="{{ $v }}" {{ ($u->$c ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
        @endforeach
        <div><label class="label text-xs">Cristales</label>
        <select name="uro[cristales]" class="input">@foreach($cristalesOpts as $v=>$l)<option value="{{ $v }}" {{ ($u->cristales ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
        <div><label class="label text-xs">Cilindros</label>
        <select name="uro[cilindros]" class="input">@foreach($cilindrosOpts as $v=>$l)<option value="{{ $v }}" {{ ($u->cilindros ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
    </div>
</div>
</div>

{{-- Tab Coprológico --}}
<div x-show="tab==='cop'" style="display:none">
@php $c = $cop; @endphp
<div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
    <div class="grid grid-cols-3 gap-4">
        <div><label class="label text-xs">Tipo Estudio</label><input type="text" name="cop[tipo_estudio]" value="{{ $c->tipo_estudio ?? 'NORMAL' }}" class="input"></div>
        <div><label class="label text-xs">Color</label><input type="text" name="cop[color]" value="{{ $c->color ?? '' }}" class="input"></div>
        <div><label class="label text-xs">Consistencia</label><input type="text" name="cop[consistencia]" value="{{ $c->consistencia ?? '' }}" class="input"></div>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="cop[sin_parasitos]" id="sin_parasitos" value="1" {{ ($c->sin_parasitos ?? false) ? 'checked' : '' }} class="w-4 h-4">
        <label for="sin_parasitos" class="text-sm font-medium text-gray-700 uppercase">No se observan elementos parasitarios en esta muestra</label>
    </div>
    <div>
        <label class="label text-xs">Se Observan (6 líneas)</label>
        <textarea name="cop[se_observan]" rows="6" class="input w-full font-mono text-sm">{{ $c->se_observan ?? '' }}</textarea>
    </div>
    <div><label class="label text-xs">Sangre Oculta</label><input type="text" name="cop[sangre_oculta]" value="{{ $c->sangre_oculta ?? '' }}" class="input w-56"></div>
    <div>
        <label class="label text-xs">Invest. de Amebas</label>
        <textarea name="cop[invest_amebas]" rows="3" class="input w-full">{{ $c->invest_amebas ?? '' }}</textarea>
    </div>
    <div>
        <label class="label text-xs">Observación</label>
        <textarea name="cop[observacion]" rows="2" class="input w-full">{{ $c->observacion ?? '' }}</textarea>
    </div>
</div>
</div>

<div class="mt-5 flex gap-3">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
        <i class="fas fa-save"></i> Grabar
    </button>
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium">Cancelar</a>
</div>
</form>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
