@extends('layouts.app')
@section('title', 'Análisis Varios — ' . $oa->orden->numero_orden)

@section('content')
@php
$paciente = $oa->orden->paciente;
$grupos = [
    'A- ANTIDOPING' => ['Marihuana','Cocaína','Anfetaminas','Barbitúricos'],
    'HEMATOLOGIA'   => ['Hemograma Completo','Grupo y Rh'],
    'HORMONALES'    => ['TSH','T3','T4','FSH','LH','Prolactina','Testosterona','Progesterona','Estradiol','Beta HCG'],
    'INMUNO-SEROLOGIA' => ['HIV 1/2','VDRL','ELISA','RPR'],
    'PRUEBAS ESPECIALES' => ['Glucosa Basal','Glucosa 2h','HbA1c','Microalbuminuria'],
    'QUIMICA CLINICA' => ['Glucosa','Urea','Creatinina','Ácido Úrico','Colesterol','Triglicéridos','HDL','LDL','TGO','TGP','Fosfatasa Alcalina','Bilirrubina Total','Bilirrubina Directa','Proteínas Totales','Albumina'],
    'SEROLOGIA'     => ['A.S.O (NIÑOS)','A.S.O (ADULTOS)','FACTOR REUMATOIDE','PROTEINA C. REACTIVA (PCR)','PRUEBA DE EMBARAZO EN SUERO','TUBERCULINA EN SANGRE','V.D.R.L.'],
];
@endphp

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-arrow-left"></i></a>
    <div>
        <h1 class="text-xl font-bold text-gray-800">Análisis Varios — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span></h1>
        <p class="text-sm text-gray-500">{{ $paciente->nombre }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Formulario de captura --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">Nuevo Análisis</h2>
        <form method="POST" action="{{ route('resultados.varios.guardar', $oa) }}" x-data="{ grupo: '' }">
        @csrf
            <div class="space-y-3">
                <div>
                    <label class="label">Grupo *</label>
                    <select name="grupo" x-model="grupo" class="input" required>
                        <option value="">— Seleccionar —</option>
                        @foreach(array_keys($grupos) as $g)
                            <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Sub-Grupo *</label>
                    <select name="sub_grupo" class="input" required>
                        <option value="">— Seleccionar grupo primero —</option>
                        @foreach($grupos as $g => $subs)
                        <template x-if="grupo === '{{ $g }}'">
                            <template x-for="s in {{ json_encode($subs) }}" :key="s">
                                <option :value="s" x-text="s"></option>
                            </template>
                        </template>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Resultado</label>
                    <textarea name="resultado" rows="2" class="input w-full"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="label text-xs">Método</label><input type="text" name="metodo" class="input"></div>
                    <div><label class="label text-xs">Medidas</label><input type="text" name="medidas" class="input"></div>
                    <div><label class="label text-xs">Muestra</label><input type="text" name="muestra" class="input"></div>
                    <div><label class="label text-xs">Valor Ref.</label><input type="text" name="valor_ref" class="input"></div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium flex items-center gap-1">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Lista de análisis reportados --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-700 text-sm"><i class="fas fa-list text-blue-500 mr-2"></i>Análisis Reportados</h2>
        </div>
        @if($resultados->count())
        <table class="w-full text-xs">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-3 py-2 text-left text-blue-800">Sub-Grupo</th>
                    <th class="px-3 py-2 text-left text-blue-800">Resultado</th>
                    <th class="px-3 py-2 text-left text-blue-800">Valor Ref.</th>
                    <th class="px-3 py-2 text-left text-blue-800">Medidas</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($resultados as $rv)
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2">
                    <span class="text-blue-600 text-xs">{{ $rv->grupo }}</span><br>
                    <span class="font-medium">{{ $rv->sub_grupo }}</span>
                </td>
                <td class="px-3 py-2">{{ $rv->resultado }}</td>
                <td class="px-3 py-2 text-gray-500">{{ $rv->valor_ref }}</td>
                <td class="px-3 py-2 text-gray-500">{{ $rv->medidas }}</td>
                <td class="px-3 py-2 text-right">
                    <form method="POST" action="{{ route('resultados.varios.eliminar', $rv) }}" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div class="px-5 py-12 text-center text-gray-400">
            <i class="fas fa-vial text-3xl mb-2 block"></i>Sin análisis registrados aún
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
