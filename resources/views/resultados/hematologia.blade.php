@extends('layouts.app')
@section('title', 'Hematología — ' . $oa->orden->numero_orden)

@section('content')
@php $r = $resultado; $paciente = $oa->orden->paciente; @endphp

{{-- Encabezado --}}
<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h1 class="text-xl font-bold text-gray-800">Hematología — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
        <p class="text-sm text-gray-500">{{ $paciente->nombre }} | {{ $paciente->edad }} años | {{ $paciente->sexo == 'F' ? 'F' : 'M' }}</p>
    </div>
    <div class="ml-auto flex gap-2">
        @if($r->validado ?? false)
            <span class="bg-green-100 text-green-800 px-3 py-1.5 rounded-lg text-sm font-medium"><i class="fas fa-check mr-1"></i>Validado</span>
        @endif
        <a href="{{ route('pdf.hematologia', $oa) }}" target="_blank"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-print"></i> Imprimir
        </a>
    </div>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'general' }">
    <div class="flex gap-1 mb-4 bg-gray-200 p-1 rounded-lg w-fit">
        <button @click="tab='general'" :class="tab==='general' ? 'bg-white shadow text-blue-700' : 'text-gray-600 hover:text-gray-800'"
            class="px-5 py-2 rounded-md text-sm font-medium transition">General</button>
        <button @click="tab='hemograma'" :class="tab==='hemograma' ? 'bg-white shadow text-blue-700' : 'text-gray-600 hover:text-gray-800'"
            class="px-5 py-2 rounded-md text-sm font-medium transition">Hemograma</button>
    </div>

    <form method="POST" action="{{ route('resultados.hematologia.guardar', $oa) }}">
    @csrf

    {{-- ── Tab General ─────────────────────────────────────────── --}}
    <div x-show="tab==='general'">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-blue-700 text-white px-5 py-3 text-sm font-semibold uppercase tracking-wider">
                Hematología I — Analizador CBC
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <label class="label">Bioanalista</label>
                    <select name="bioanalista_id" class="input w-56">
                        <option value="">— Seleccionar —</option>
                        @foreach($bioanalistas as $bio)
                            <option value="{{ $bio->id }}" {{ ($r->bioanalista_id ?? '') == $bio->id ? 'selected' : '' }}>
                                {{ $bio->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="overflow-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-blue-50">
                            <th class="px-3 py-2 text-left text-blue-800 border border-blue-200 w-44">Parámetro</th>
                            <th class="px-3 py-2 text-center text-blue-800 border border-blue-200">Resultado</th>
                            <th class="px-3 py-2 text-center text-blue-800 border border-blue-200 w-28">Unidad</th>
                            <th class="px-3 py-2 text-center text-blue-800 border border-blue-200">Referencia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @php
                    $params = [
                        ['name'=>'wbc',        'label'=>'WBC',        'unit'=>'10³/UL',  'ref'=>'4.0 – 10.0'],
                        ['name'=>'lymph_abs',   'label'=>'Lymph#',     'unit'=>'10³/UL',  'ref'=>'0.60 – 4.10'],
                        ['name'=>'mid_abs',     'label'=>'Mid#',       'unit'=>'10³/UL',  'ref'=>'0.10 – 0.90'],
                        ['name'=>'gran_abs',    'label'=>'Gran#',      'unit'=>'10³/UL',  'ref'=>'2.00 – 7.80'],
                        ['name'=>'lymph_pct',   'label'=>'Lymph%',     'unit'=>'%',       'ref'=>'20.0 – 50.0'],
                        ['name'=>'mid_pct',     'label'=>'Mid%',       'unit'=>'%',       'ref'=>'3.0 – 10.0'],
                        ['name'=>'gran_pct',    'label'=>'Gran%',      'unit'=>'%',       'ref'=>'40.0 – 70.0'],
                        ['name'=>'rbc',         'label'=>'RBC',        'unit'=>'10³/UL',  'ref'=>'3.80 – 5.80'],
                        ['name'=>'hgb',         'label'=>'HGB',        'unit'=>'g/dL',    'ref'=>'11.0 – 16.5'],
                        ['name'=>'hct',         'label'=>'HCT',        'unit'=>'%',       'ref'=>'35.0 – 50.0'],
                        ['name'=>'mcv',         'label'=>'MCV',        'unit'=>'fL',      'ref'=>'80.0 – 100.0'],
                        ['name'=>'mch',         'label'=>'MCH',        'unit'=>'pg',      'ref'=>'26.5 – 33.5'],
                        ['name'=>'mchc',        'label'=>'MCHC',       'unit'=>'g/dL',    'ref'=>'32.2 – 36.0'],
                        ['name'=>'rdw_cv',      'label'=>'RDW-CV',     'unit'=>'%',       'ref'=>'10.0 – 15.0'],
                        ['name'=>'rdw_sd',      'label'=>'RDW-SD',     'unit'=>'fL',      'ref'=>'35.0 – 56.0'],
                        ['name'=>'plt',         'label'=>'PLT',        'unit'=>'10³/UL',  'ref'=>'150 – 450'],
                        ['name'=>'mpv',         'label'=>'MPV',        'unit'=>'fL',      'ref'=>'7.0 – 11.0'],
                        ['name'=>'pdw',         'label'=>'PDW',        'unit'=>'%',       'ref'=>'10.0 – 18.0'],
                        ['name'=>'pct',         'label'=>'PCT',        'unit'=>'%',       'ref'=>'0.100 – 0.500'],
                        ['name'=>'plcr',        'label'=>'P-LCR',      'unit'=>'%',       'ref'=>'13.0 – 43.0'],
                        ['name'=>'vitamina_b12','label'=>'Vitamina B12','unit'=>'10³/UL', 'ref'=>'—'],
                        ['name'=>'acido_folico','label'=>'Ácido Fólico','unit'=>'10³/UL', 'ref'=>'—'],
                        ['name'=>'hierro',      'label'=>'Hierro',     'unit'=>'10³/UL',  'ref'=>'—'],
                    ];
                    @endphp
                    @foreach($params as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-1.5 border border-gray-200 font-medium text-gray-700">{{ $p['label'] }}</td>
                        <td class="px-2 py-1 border border-gray-200">
                            <input type="number" step="any" name="{{ $p['name'] }}" value="{{ $r->{$p['name']} ?? '' }}"
                                class="w-full text-center border-0 focus:ring-1 focus:ring-blue-400 rounded outline-none text-sm py-0.5">
                        </td>
                        <td class="px-3 py-1.5 border border-gray-200 text-center text-xs text-gray-500">{{ $p['unit'] }}</td>
                        <td class="px-3 py-1.5 border border-gray-200 text-center text-xs text-gray-400">{{ $p['ref'] }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>

                <div class="mt-4">
                    <label class="label">Observación</label>
                    <textarea name="observacion_general" rows="3" class="input w-full">{{ $r->observacion_general ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab Hemograma ────────────────────────────────────────── --}}
    <div x-show="tab==='hemograma'" style="display:none">
        <div class="bg-white rounded-xl shadow-sm p-5 space-y-6">

            {{-- Hemograma Completo --}}
            <div>
                <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">Hemograma Completo</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="label text-xs">Hemoglobina (g/dL)</label>
                        <input type="number" step="any" name="hemoglobina_gdl" value="{{ $r->hemoglobina_gdl ?? '' }}" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Hemoglobina (%)</label>
                        <input type="number" step="any" name="hemoglobina_pct" value="{{ $r->hemoglobina_pct ?? '' }}" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Hematocrito (%)</label>
                        <input type="number" step="any" name="hematocrito_pct" value="{{ $r->hematocrito_pct ?? '' }}" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Eritrocitos (/mn)</label>
                        <input type="text" name="eritrocitos" value="{{ $r->eritrocitos ?? '' }}" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Leucocitos (/mn)</label>
                        <input type="text" name="leucocitos" value="{{ $r->leucocitos ?? '' }}" class="input">
                    </div>
                </div>
            </div>

            {{-- Índices Hematícos --}}
            <div>
                <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">Índices Hemáticos</h3>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="label text-xs">VCM <span class="text-gray-400">(ref: 81–104)</span></label>
                        <input type="number" step="any" name="vcm" value="{{ $r->vcm ?? '' }}" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">HCM <span class="text-gray-400">(ref: 27–31)</span></label>
                        <input type="number" step="any" name="hcm" value="{{ $r->hcm ?? '' }}" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">CHCM (%) <span class="text-gray-400">(ref: 32–36)</span></label>
                        <input type="number" step="any" name="chcm" value="{{ $r->chcm ?? '' }}" class="input">
                    </div>
                </div>
            </div>

            {{-- Recuento Diferencial --}}
            <div>
                <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">% Recuento Diferencial</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach(['mieloblastos','promielocitos','mielocitos','metamielocitos','bandas','segmentos','linfocitos','monocitos','eosinofilos','basofilos'] as $campo)
                    <div>
                        <label class="label text-xs">{{ strtoupper($campo) }}</label>
                        <input type="number" step="any" name="{{ $campo }}" value="{{ $r->$campo ?? '' }}" class="input">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Observaciones morfológicas --}}
            <div>
                <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">Observaciones Morfológicas</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach(['hipocromia'=>'Hipocromia','poiquilocitosis'=>'Poiquilocitosis','anisocitosis'=>'Anisocitosis','cls_en_diana'=>'Cls. en Diana','macrocitosis'=>'Macrocitosis','cls_crenadas'=>'Cls. Crenadas','microcitosis'=>'Microcitosis','macroplaquet'=>'Macroplaquet.'] as $campo => $etiqueta)
                    <div>
                        <label class="label text-xs">{{ $etiqueta }}</label>
                        <input type="text" name="{{ $campo }}" value="{{ $r->$campo ?? '' }}" class="input">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Determinaciones --}}
            <div>
                <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider border-b border-blue-200 pb-1 mb-3">Determinaciones</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php $dets = ['eritrosedimentacion'=>'Eritrosedim. (mm/h)','conteo_eosinofilos'=>'Conteo Eosinófilos','conteo_plaquetas'=>'Conteo Plaquetas','conteo_reticulocitos'=>'Conteo Reticulocitos','reticulocitos_corregidos'=>'Reticulocitos Corr.','inv_falcemia'=>'Inv. Falcemia','inv_celulas_le'=>'Inv. Células L.E.','inv_hematozoarios'=>'Inv. Hematozoarios']; @endphp
                    @foreach($dets as $campo => $etiqueta)
                    <div>
                        <label class="label text-xs">{{ $etiqueta }}</label>
                        <input type="text" name="{{ $campo }}" value="{{ $r->$campo ?? '' }}" class="input">
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="label">Observación</label>
                <textarea name="observacion_hemograma" rows="3" class="input w-full">{{ $r->observacion_hemograma ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="mt-5 flex gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2">
            <i class="fas fa-save"></i> Grabar
        </button>
        @if(!($r->validado ?? false))
        @role('admin|bioanalista')
        <button type="button" onclick="if(confirm('¿Validar resultado?')) document.getElementById('form-validar').submit()"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2">
            <i class="fas fa-check-double"></i> Validar
        </button>
        @endrole
        @endif
        <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium">
            Cancelar
        </a>
    </div>
    </form>

    <form id="form-validar" method="POST" action="{{ route('resultados.hematologia.validar', $oa) }}" class="hidden">@csrf</form>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
