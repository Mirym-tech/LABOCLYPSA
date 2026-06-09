@extends('layouts.app')
@section('title', 'Bacteriología — ' . $oa->orden->numero_orden)

@section('content')
@php $r = $resultado; $paciente = $oa->orden->paciente; @endphp

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h1 class="text-xl font-bold text-gray-800">Bacteriología — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
        <p class="text-sm text-gray-500">{{ $paciente->nombre }}</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('pdf.bacteriologia', $oa) }}" target="_blank"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-print"></i> Imprimir
        </a>
    </div>
</div>

<div x-data="{ tab: 'general' }">
<div class="flex gap-1 mb-4 bg-gray-200 p-1 rounded-lg w-fit">
    <button @click="tab='general'" :class="tab==='general' ? 'bg-white shadow text-blue-700':'text-gray-600'" class="px-4 py-2 rounded-md text-sm font-medium">General</button>
    <button @click="tab='cont'" :class="tab==='cont' ? 'bg-white shadow text-blue-700':'text-gray-600'" class="px-4 py-2 rounded-md text-sm font-medium">Cont.</button>
    <button @click="tab='cont3'" :class="tab==='cont3' ? 'bg-white shadow text-blue-700':'text-gray-600'" class="px-4 py-2 rounded-md text-sm font-medium">Cont. 3</button>
</div>

<form method="POST" action="{{ route('resultados.bacteriologia.guardar', $oa) }}">
@csrf

{{-- Bioanalista --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-4 items-end">
    <div>
        <label class="label">Bioanalista</label>
        <select name="bioanalista_id" class="input w-56">
            <option value="">— Seleccionar —</option>
            @foreach($bioanalistas as $bio)
                <option value="{{ $bio->id }}" {{ ($r->bioanalista_id ?? '') == $bio->id ? 'selected' : '' }}>{{ $bio->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1">
        <label class="label">Estudio</label>
        <select name="estudio" class="input">
            <option value="">— Seleccionar —</option>
            @foreach(['Descripción Cultivo','Cultivo de Absceso','Cultivo de BK','Cultivo de Esputo','Cultivo de Fluidos','Cultivo de Garganta','Cultivo de Heces Fecales','Cultivo de Heridas','Cultivo de LCR','Cultivo de Oído','Cultivo de Orina','Cultivo de Secreción Vaginal','Cultivo de Uretra'] as $e)
                <option value="{{ $e }}" {{ ($r->estudio ?? '') == $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1">
        <label class="label">Muestra de</label>
        <input type="text" name="muestra_de" value="{{ $r->muestra_de ?? '' }}" class="input">
    </div>
</div>

{{-- Tab General: Organismo + Antibiograma 1 --}}
<div x-show="tab==='general'">
<div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="label">Organismo(s)</label>
            <select name="organismo" class="input">
                <option value="">— Seleccionar —</option>
                @foreach(['Ningún Crecimiento De Microorganismo A Las 72 Horas De Incubación','Estafilococus Aereus','Pseudomonas Aeroginosas','Proteus Mirabilis','Proteus Spp','Proteus Vulgaris','Escherichia Coli','Enterobacter Aerogenes','Klebsiella Pneumonide','Klebsiella Spp','Haemofilus Influenzae','Candida Albicans','Candida Spp','Enterococcus Beta Hemolítico Grupo A'] as $org)
                    <option value="{{ $org }}" {{ ($r->organismo ?? '') == $org ? 'selected' : '' }}>{{ $org }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Aislado(s)</label>
            <input type="text" name="aislados" value="{{ $r->aislados ?? '' }}" class="input">
        </div>
    </div>

    <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1">Antibiograma</h3>
    @php
    $anti1L = ['penicilina','piperacilina','carbenicilina','ampicilina','amoxicilina','cefalexina','cefotaxina'];
    $anti1R = ['norfloxacin','karamicina','gentamicina','tabramicina','amikacina','ceftriazona','cefazolin'];
    $opts = [''=>'—','S'=>'S - Sensible','R'=>'R - Resistente','I'=>'I - Intermedio'];
    @endphp
    <div class="grid grid-cols-2 gap-6">
        <table class="text-sm w-full">
            <thead><tr class="bg-blue-50"><th class="px-3 py-2 text-left">Antibiótico</th><th class="px-3 py-2 text-center w-24">S/R</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($anti1L as $a)
            <tr><td class="px-3 py-1.5">{{ strtoupper(str_replace('_',' ',$a)) }}</td>
            <td class="px-2 py-1">
                <select name="{{ $a }}" class="w-full border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-blue-400">
                    @foreach($opts as $v=>$l)<option value="{{ $v }}" {{ ($r->$a ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                </select>
            </td></tr>
            @endforeach
            </tbody>
        </table>
        <table class="text-sm w-full">
            <thead><tr class="bg-blue-50"><th class="px-3 py-2 text-left">Antibiótico</th><th class="px-3 py-2 text-center w-24">S/R</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($anti1R as $a)
            <tr><td class="px-3 py-1.5">{{ strtoupper(str_replace('_',' ',$a)) }}</td>
            <td class="px-2 py-1">
                <select name="{{ $a }}" class="w-full border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-blue-400">
                    @foreach($opts as $v=>$l)<option value="{{ $v }}" {{ ($r->$a ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                </select>
            </td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

{{-- Tab Cont. --}}
<div x-show="tab==='cont'" style="display:none">
<div class="bg-white rounded-xl shadow-sm p-5">
    @php
    $anti2L = ['tetraciclina','minociclina','eritrociclina','lincomicina','fosfocil','cefepime','ac_nalidixico','amox_ac_clav'];
    $anti2R = ['levofloxacin','furadantoina','ciproflaxacina','clindamicina','sulfatrym','vancomicina','imipenen','cefunoxima'];
    @endphp
    <div class="grid grid-cols-2 gap-6">
        <table class="text-sm w-full">
            <thead><tr class="bg-blue-50"><th class="px-3 py-2 text-left">Antibiótico</th><th class="px-3 py-2 text-center w-24">S/R</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($anti2L as $a)
            <tr><td class="px-3 py-1.5">{{ strtoupper(str_replace('_',' ',$a)) }}</td>
            <td class="px-2 py-1"><select name="{{ $a }}" class="w-full border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-blue-400">
                @foreach($opts as $v=>$l)<option value="{{ $v }}" {{ ($r->$a ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select></td></tr>
            @endforeach
            </tbody>
        </table>
        <table class="text-sm w-full">
            <thead><tr class="bg-blue-50"><th class="px-3 py-2 text-left">Antibiótico</th><th class="px-3 py-2 text-center w-24">S/R</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($anti2R as $a)
            <tr><td class="px-3 py-1.5">{{ strtoupper(str_replace('_',' ',$a)) }}</td>
            <td class="px-2 py-1"><select name="{{ $a }}" class="w-full border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-blue-400">
                @foreach($opts as $v=>$l)<option value="{{ $v }}" {{ ($r->$a ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

{{-- Tab Cont. 3 — Examen Microscópico --}}
<div x-show="tab==='cont3'" style="display:none">
<div class="bg-white rounded-xl shadow-sm p-5 space-y-4">
    <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1">Examen Microscópico</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach(['epitelios'=>'Epitelios','leucocitos_micro'=>'Leucocitos','hematies'=>'Hematies','tincion_gram'=>'Tinción de Gram','tincion_ziehl'=>'Tinción Ziehl Neelsen','bacterias'=>'Bacterias','levaduras'=>'Levaduras','t_vaginalis'=>'T. Vaginalis'] as $campo => $etiqueta)
        <div>
            <label class="label text-xs">{{ $etiqueta }}</label>
            <input type="text" name="{{ $campo }}" value="{{ $r->$campo ?? '' }}" class="input">
        </div>
        @endforeach
    </div>
    <div>
        <label class="label">Observación</label>
        <textarea name="observacion" rows="3" class="input w-full">{{ $r->observacion ?? '' }}</textarea>
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
