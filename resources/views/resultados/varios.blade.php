@extends('layouts.app')
@section('title', 'Análisis Varios — ' . $oa->orden->numero_orden)

@section('content')
@php
$paciente = $oa->orden->paciente;
$grupos = [
    'A- ANTIDOPING'      => ['Marihuana','Cocaína','Anfetaminas','Barbitúricos'],
    'HEMATOLOGIA'        => ['Hemograma Completo','Grupo y Rh'],
    'HORMONALES'         => ['TSH','T3','T4','FSH','LH','Prolactina','Testosterona','Progesterona','Estradiol','Beta HCG'],
    'INMUNO-SEROLOGIA'   => ['HIV 1/2','VDRL','ELISA','RPR'],
    'PRUEBAS ESPECIALES' => ['Glucosa Basal','Glucosa 2h','HbA1c','Microalbuminuria'],
    'QUIMICA CLINICA'    => ['Glucosa','Urea','Creatinina','Ácido Úrico','Colesterol','Triglicéridos','HDL','LDL','TGO','TGP','Fosfatasa Alcalina','Bilirrubina Total','Bilirrubina Directa','Proteínas Totales','Albumina'],
    'SEROLOGIA'          => ['A.S.O (NIÑOS)','A.S.O (ADULTOS)','FACTOR REUMATOIDE','PROTEINA C. REACTIVA (PCR)','PRUEBA DE EMBARAZO EN SUERO','TUBERCULINA EN SANGRE','V.D.R.L.'],
];
$urlActualizar = url('resultados/varios/item');
@endphp

<div class="flex flex-wrap items-start gap-2 mb-5">
    <a href="{{ route('ordenes.show', $oa->orden_id) }}" class="text-gray-400 hover:text-gray-600 mt-1 flex-shrink-0">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="min-w-0 flex-1">
        <h1 class="text-lg sm:text-xl font-bold text-gray-800 leading-tight">
            Análisis Varios — Orden <span class="text-blue-600 font-mono">{{ $oa->orden->numero_orden }}</span>
        </h1>
        <p class="text-sm text-gray-500">{{ $paciente->nombre }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6"
     x-data="{
         modo: 'nuevo',
         editId: null,
         grupo: '',
         subGrupo: '',
         resultado: '',
         metodo: '',
         medidas: '',
         muestra: '',
         valorRef: '',
         editarItem(item) {
             this.modo    = 'editar';
             this.editId  = item.id;
             this.grupo   = item.grupo;
             this.resultado = item.resultado ?? '';
             this.metodo  = item.metodo ?? '';
             this.medidas = item.medidas ?? '';
             this.muestra = item.muestra ?? '';
             this.valorRef = item.valor_ref ?? '';
             this.$nextTick(() => { this.subGrupo = item.sub_grupo; });
             document.getElementById('panel-formulario').scrollIntoView({ behavior: 'smooth', block: 'start' });
         },
         cancelar() {
             this.modo     = 'nuevo';
             this.editId   = null;
             this.grupo    = '';
             this.subGrupo = '';
             this.resultado = '';
             this.metodo   = '';
             this.medidas  = '';
             this.muestra  = '';
             this.valorRef = '';
         }
     }">

    {{-- ── Formulario (nuevo / editar) ──────────────────────────────────────── --}}
    <div id="panel-formulario"
         class="bg-white rounded-xl shadow-sm p-5 transition-all"
         :class="modo === 'editar' ? 'ring-2 ring-amber-400' : ''">

        <h2 class="text-sm font-semibold uppercase tracking-wider mb-4 border-b pb-2 flex items-center gap-2"
            :class="modo === 'editar' ? 'text-amber-600' : 'text-gray-500'">
            <i :class="modo === 'editar' ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
            <span x-text="modo === 'editar' ? 'Editar Análisis' : 'Nuevo Análisis'"></span>
        </h2>

        <form method="POST"
              :action="modo === 'editar' ? '{{ $urlActualizar }}/' + editId : '{{ route('resultados.varios.guardar', $oa) }}'">
            @csrf
            {{-- PUT cuando estamos editando --}}
            <template x-if="modo === 'editar'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="space-y-3">

                {{-- Grupo --}}
                <div>
                    <label class="label">Grupo *</label>
                    <select name="grupo" x-model="grupo" class="input" required>
                        <option value="">— Seleccionar —</option>
                        @foreach(array_keys($grupos) as $g)
                            <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sub-Grupo: select dinámico al crear, texto libre al editar --}}
                <div>
                    <label class="label">Sub-Grupo *</label>
                    <template x-if="modo === 'nuevo'">
                        <select name="sub_grupo" x-model="subGrupo" class="input" required>
                            <option value="">— Seleccionar grupo primero —</option>
                            @foreach($grupos as $g => $subs)
                            <template x-if="grupo === '{{ $g }}'">
                                <template x-for="s in {{ json_encode($subs) }}" :key="s">
                                    <option :value="s" x-text="s"></option>
                                </template>
                            </template>
                            @endforeach
                        </select>
                    </template>
                    <template x-if="modo === 'editar'">
                        <input type="text" name="sub_grupo" x-model="subGrupo" class="input" required
                               placeholder="Ej: Glucosa Basal">
                    </template>
                </div>

                {{-- Resultado --}}
                <div>
                    <label class="label">Resultado</label>
                    <textarea name="resultado" rows="2" class="input w-full" x-model="resultado"></textarea>
                </div>

                {{-- Campos secundarios --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label text-xs">Método</label>
                        <input type="text" name="metodo" x-model="metodo" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Medidas</label>
                        <input type="text" name="medidas" x-model="medidas" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Muestra</label>
                        <input type="text" name="muestra" x-model="muestra" class="input">
                    </div>
                    <div>
                        <label class="label text-xs">Valor Ref.</label>
                        <input type="text" name="valor_ref" x-model="valorRef" class="input">
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="flex gap-2 flex-wrap pt-1">
                    <button type="submit"
                            :class="modo === 'editar'
                                ? 'bg-amber-500 hover:bg-amber-600'
                                : 'bg-blue-600 hover:bg-blue-700'"
                            class="text-white px-5 py-2 rounded-lg text-sm font-medium flex items-center gap-1.5">
                        <i :class="modo === 'editar' ? 'fas fa-save' : 'fas fa-plus'"></i>
                        <span x-text="modo === 'editar' ? 'Actualizar' : 'Agregar'"></span>
                    </button>

                    <template x-if="modo === 'editar'">
                        <button type="button" @click="cancelar()"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-times mr-1"></i>Cancelar edición
                        </button>
                    </template>

                    <a href="{{ route('ordenes.show', $oa->orden_id) }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium">
                        Volver a la orden
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- ── Lista de análisis reportados ─────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-700 text-sm">
                <i class="fas fa-list text-blue-500 mr-2"></i>Análisis Reportados
            </h2>
        </div>

        @if($resultados->count())
        <table class="w-full text-xs">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-3 py-2 text-left text-blue-800">Sub-Grupo</th>
                    <th class="px-3 py-2 text-left text-blue-800">Resultado</th>
                    <th class="px-3 py-2 text-left text-blue-800">Valor Ref.</th>
                    <th class="px-3 py-2 text-left text-blue-800">Medidas</th>
                    <th class="px-3 py-2 w-16"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($resultados as $rv)
            <tr class="hover:bg-gray-50"
                :class="editId === {{ $rv->id }} ? 'bg-amber-50 outline outline-1 outline-amber-300' : ''">
                <td class="px-3 py-2 cursor-pointer"
                    @click="editarItem({{ json_encode([
                        'id'        => $rv->id,
                        'grupo'     => $rv->grupo,
                        'sub_grupo' => $rv->sub_grupo,
                        'resultado' => $rv->resultado,
                        'valor_ref' => $rv->valor_ref,
                        'medidas'   => $rv->medidas,
                        'metodo'    => $rv->metodo,
                        'muestra'   => $rv->muestra,
                    ]) }})">
                    <span class="text-blue-600 font-medium text-xs">{{ $rv->grupo }}</span><br>
                    <span class="font-semibold text-gray-800">{{ $rv->sub_grupo }}</span>
                </td>
                <td class="px-3 py-2 text-gray-700">{{ $rv->resultado }}</td>
                <td class="px-3 py-2 text-gray-500">{{ $rv->valor_ref }}</td>
                <td class="px-3 py-2 text-gray-500">{{ $rv->medidas }}</td>
                <td class="px-3 py-2">
                    <div class="flex items-center gap-2 justify-end">
                        {{-- Editar --}}
                        <button type="button" title="Editar"
                                @click="editarItem({{ json_encode([
                                    'id'        => $rv->id,
                                    'grupo'     => $rv->grupo,
                                    'sub_grupo' => $rv->sub_grupo,
                                    'resultado' => $rv->resultado,
                                    'valor_ref' => $rv->valor_ref,
                                    'medidas'   => $rv->medidas,
                                    'metodo'    => $rv->metodo,
                                    'muestra'   => $rv->muestra,
                                ]) }})"
                                class="text-amber-500 hover:text-amber-700">
                            <i class="fas fa-edit"></i>
                        </button>
                        {{-- Eliminar --}}
                        <form method="POST" action="{{ route('resultados.varios.eliminar', $rv) }}"
                              onsubmit="return confirm('¿Eliminar este análisis?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Eliminar" class="text-red-400 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div class="px-5 py-12 text-center text-gray-400">
            <i class="fas fa-vial text-3xl mb-2 block"></i>
            Sin análisis registrados aún
        </div>
        @endif
    </div>

</div>
@endsection
