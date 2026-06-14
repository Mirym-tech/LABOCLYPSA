@extends('layouts.app')
@section('title', 'Orden ' . $orden->numero_orden)

@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm flex items-center gap-2">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- Encabezado responsive --}}
<div class="flex flex-wrap items-start gap-2 mb-6">
    <a href="{{ route('ordenes.index') }}" class="text-gray-400 hover:text-gray-600 mt-1 flex-shrink-0"><i class="fas fa-arrow-left"></i></a>
    <div class="min-w-0 flex-1">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">Orden <span class="text-blue-600 font-mono">{{ $orden->numero_orden }}</span></h1>
        <p class="text-sm text-gray-500">Creada el {{ $orden->created_at?->format('d/m/Y H:i') }} por {{ $orden->creadoPor?->name }}</p>
    </div>
    @if($orden->estado !== 'validado')
    @role('admin|bioanalista')
    <form method="POST" action="{{ route('ordenes.validar', $orden) }}" class="flex-shrink-0">
        @csrf
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-double"></i> <span class="hidden xs:inline">Validar Orden</span><span class="xs:hidden">Validar</span>
        </button>
    </form>
    @endrole
    @endif
    @if($orden->analisis->where('estado', 'listo')->isNotEmpty())
    <a href="{{ route('pdf.orden', $orden) }}" target="_blank"
       class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-file-pdf"></i> <span class="hidden xs:inline">Imprimir Todo</span><span class="xs:hidden"><i class="fas fa-print"></i></span>
    </a>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Info del paciente --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-2">Datos del Paciente</h2>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <div><span class="text-gray-400">Nombre:</span> <strong>{{ $orden->paciente->nombre }}</strong></div>
            <div><span class="text-gray-400">Edad:</span> {{ $orden->paciente->edad }} años</div>
            <div><span class="text-gray-400">Sexo:</span> {{ $orden->paciente->sexo == 'F' ? 'Femenino' : 'Masculino' }}</div>
            <div><span class="text-gray-400">Tipo:</span> {{ ucfirst($orden->tipo_paciente) }}</div>
            <div><span class="text-gray-400">Médico:</span> {{ $orden->paciente->medico_tratante ?? '—' }}</div>
            <div><span class="text-gray-400">Seguro:</span> {{ $orden->paciente->seguro_medico ?? 'SIN SEGURO' }}</div>
            <div><span class="text-gray-400">Factura:</span> {{ $orden->numero_factura ?? '—' }}</div>
            <div><span class="text-gray-400">Laboratorio:</span> {{ $orden->laboratorio->nombre }}</div>
            @if($orden->embarazada)<div class="col-span-2"><span class="bg-pink-100 text-pink-700 px-2 py-0.5 rounded text-xs font-medium">Embarazada</span></div>@endif
        </div>
    </div>

    {{-- Estado --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-2">Estado</h2>
        @php $colores = ['pendiente'=>'yellow','en_proceso'=>'blue','listo'=>'green','por_validar'=>'orange','validado'=>'gray']; $c = $colores[$orden->estado] ?? 'gray'; @endphp
        <div class="text-center py-4">
            <span class="inline-block bg-{{ $c }}-100 text-{{ $c }}-800 text-lg font-semibold px-4 py-2 rounded-full">
                {{ ucfirst(str_replace('_',' ',$orden->estado)) }}
            </span>
            @if($orden->validado_por)
                <p class="text-xs text-gray-400 mt-2">Validado por {{ $orden->validadoPor->name }}</p>
                <p class="text-xs text-gray-400">{{ $orden->validado_at?->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    </div>
</div>

{{-- Análisis Solicitados --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b bg-gray-50">
        <h2 class="font-semibold text-gray-700"><i class="fas fa-vials text-blue-500 mr-2"></i>Análisis Solicitados</h2>
    </div>

    {{-- Mobile: cards apiladas --}}
    <div class="divide-y divide-gray-100 sm:hidden">
    @foreach($orden->analisis as $oa)
        @php
            $ruta = match($oa->tipo->categoria) {
                'HEMATOLOGIA', 'HEMATO/COAGULACION' => route('resultados.hematologia', $oa),
                'BACTERIOLOGIA'                      => route('resultados.bacteriologia', $oa),
                'ANTIGENOS', 'SEROLOGIA'             => route('resultados.serologia', $oa),
                'ANALISIS DE COLERA'                 => route('resultados.colera', $oa),
                'UROANALISIS'                        => route('resultados.uroanalisis', $oa),
                'DIGESTION EN HECES'                 => route('resultados.digestion', $oa),
                'ANALISIS VARIOS'                    => route('resultados.varios', $oa),
                default => null,
            };
            $pdfRuta = match($oa->tipo->categoria) {
                'HEMATOLOGIA', 'HEMATO/COAGULACION' => route('pdf.hematologia', $oa),
                'BACTERIOLOGIA'                      => route('pdf.bacteriologia', $oa),
                'ANTIGENOS', 'SEROLOGIA'             => route('pdf.serologia', $oa),
                'ANALISIS DE COLERA'                 => route('pdf.colera', $oa),
                'UROANALISIS' => str_contains(strtolower($oa->tipo->nombre ?? ''), 'coprol')
                                     ? route('pdf.coprologia', $oa)
                                     : route('pdf.uroanalisis', $oa),
                'DIGESTION EN HECES'                 => route('pdf.digestion', $oa),
                'ANALISIS VARIOS'                    => route('pdf.varios', $oa),
                default => null,
            };
            $tieneResultado = match($oa->tipo->categoria) {
                'HEMATOLOGIA', 'HEMATO/COAGULACION' => $oa->resultadoHematologia !== null,
                'BACTERIOLOGIA'                      => $oa->resultadoBacteriologia !== null,
                'ANTIGENOS', 'SEROLOGIA'             => $oa->resultadoSerologia !== null,
                'ANALISIS DE COLERA'                 => $oa->resultadoColera !== null,
                'UROANALISIS'                        => $oa->resultadoUroanalisis !== null || $oa->resultadoCoprologia !== null,
                'DIGESTION EN HECES'                 => $oa->resultadoDigestion !== null,
                'ANALISIS VARIOS'                    => $oa->resultadoVarios->isNotEmpty(),
                default                              => false,
            };
            $coloresEstado = ['pendiente'=>'yellow','en_proceso'=>'blue','listo'=>'green'];
            $ce = $coloresEstado[$oa->estado] ?? 'gray';
        @endphp
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-mono text-blue-600 text-xs">{{ $oa->tipo->codigo }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $ce }}-100 text-{{ $ce }}-800 font-medium">{{ ucfirst($oa->estado) }}</span>
                </div>
                <p class="font-medium text-gray-800 text-sm leading-tight mt-0.5">{{ $oa->tipo->nombre }}</p>
                <p class="text-xs text-gray-400">{{ $oa->tipo->categoria }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($ruta)
                    @if($tieneResultado)
                        <a href="{{ $ruta }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-xs font-medium">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                    @else
                        <a href="{{ $ruta }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-medium">
                            <i class="fas fa-plus-circle mr-1"></i>Ingresar
                        </a>
                    @endif
                @endif
                @if($pdfRuta && $oa->estado === 'listo')
                    <a href="{{ $pdfRuta }}" target="_blank" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-file-pdf text-xl"></i>
                    </a>
                @endif
            </div>
        </div>
    @endforeach
    </div>

    {{-- Desktop: tabla normal --}}
    <table class="w-full text-sm hidden sm:table">
        <thead class="bg-blue-50">
            <tr>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Análisis</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Categoría</th>
                <th class="px-4 py-3 text-left text-blue-800 font-semibold">Estado</th>
                <th class="px-4 py-3 text-center text-blue-800 font-semibold">Resultado</th>
                <th class="px-4 py-3 text-center text-blue-800 font-semibold">PDF</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @foreach($orden->analisis as $oa)
            @php
                $ruta = match($oa->tipo->categoria) {
                    'HEMATOLOGIA', 'HEMATO/COAGULACION' => route('resultados.hematologia', $oa),
                    'BACTERIOLOGIA'                      => route('resultados.bacteriologia', $oa),
                    'ANTIGENOS', 'SEROLOGIA'             => route('resultados.serologia', $oa),
                    'ANALISIS DE COLERA'                 => route('resultados.colera', $oa),
                    'UROANALISIS'                        => route('resultados.uroanalisis', $oa),
                    'DIGESTION EN HECES'                 => route('resultados.digestion', $oa),
                    'ANALISIS VARIOS'                    => route('resultados.varios', $oa),
                    default => null,
                };
                $pdfRuta = match($oa->tipo->categoria) {
                    'HEMATOLOGIA', 'HEMATO/COAGULACION' => route('pdf.hematologia', $oa),
                    'BACTERIOLOGIA'                      => route('pdf.bacteriologia', $oa),
                    'ANTIGENOS', 'SEROLOGIA'             => route('pdf.serologia', $oa),
                    'ANALISIS DE COLERA'                 => route('pdf.colera', $oa),
                    'UROANALISIS' => str_contains(strtolower($oa->tipo->nombre ?? ''), 'coprol')
                                     ? route('pdf.coprologia', $oa)
                                     : route('pdf.uroanalisis', $oa),
                    'DIGESTION EN HECES'                 => route('pdf.digestion', $oa),
                    'ANALISIS VARIOS'                    => route('pdf.varios', $oa),
                    default => null,
                };
                $tieneResultado = match($oa->tipo->categoria) {
                    'HEMATOLOGIA', 'HEMATO/COAGULACION' => $oa->resultadoHematologia !== null,
                    'BACTERIOLOGIA'                      => $oa->resultadoBacteriologia !== null,
                    'ANTIGENOS', 'SEROLOGIA'             => $oa->resultadoSerologia !== null,
                    'ANALISIS DE COLERA'                 => $oa->resultadoColera !== null,
                    'UROANALISIS'                        => $oa->resultadoUroanalisis !== null || $oa->resultadoCoprologia !== null,
                    'DIGESTION EN HECES'                 => $oa->resultadoDigestion !== null,
                    'ANALISIS VARIOS'                    => $oa->resultadoVarios->isNotEmpty(),
                    default                              => false,
                };
                $coloresEstado = ['pendiente'=>'yellow','en_proceso'=>'blue','listo'=>'green'];
                $ce = $coloresEstado[$oa->estado] ?? 'gray';
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <span class="font-mono text-blue-600 text-xs mr-1">{{ $oa->tipo->codigo }}</span>
                    {{ $oa->tipo->nombre }}
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $oa->tipo->categoria }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $ce }}-100 text-{{ $ce }}-800">
                        {{ ucfirst($oa->estado) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    @if($ruta)
                        @if($tieneResultado)
                            <a href="{{ $ruta }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>Editar
                            </a>
                        @else
                            <a href="{{ $ruta }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-plus-circle mr-1"></i>Ingresar
                            </a>
                        @endif
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    @if($pdfRuta && $oa->estado === 'listo')
                        <a href="{{ $pdfRuta }}" target="_blank" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-file-pdf text-lg"></i>
                        </a>
                    @else
                        <span class="text-gray-200"><i class="fas fa-file-pdf"></i></span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if($categorias->isNotEmpty())
{{-- Agregar análisis a la orden --}}
<div class="mt-6" x-data="{ abierto: false }">
    <button @click="abierto = !abierto"
            class="w-full flex items-center justify-between bg-white rounded-xl shadow-sm px-5 py-4 text-left hover:bg-gray-50 transition-colors">
        <span class="font-semibold text-gray-700">
            <i class="fas fa-plus-circle text-blue-500 mr-2"></i>Agregar Análisis a esta Orden
        </span>
        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="abierto ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="abierto" x-transition class="mt-2 bg-white rounded-xl shadow-sm p-5">
        <form method="POST" action="{{ route('ordenes.agregarAnalisis', $orden) }}">
            @csrf
            @error('analisis_ids')
                <p class="text-red-600 text-sm mb-3"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
                @foreach($categorias as $cat => $tipos)
                <div class="border border-gray-100 rounded-lg p-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b">{{ $cat }}</h3>
                    <div class="space-y-2">
                        @foreach($tipos as $tipo)
                        <label class="flex items-start gap-2 cursor-pointer group">
                            <input type="checkbox" name="analisis_ids[]" value="{{ $tipo->id }}"
                                   class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm leading-tight group-hover:text-blue-700">
                                <span class="font-mono text-blue-500 text-xs">{{ $tipo->codigo }}</span>
                                {{ $tipo->nombre }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-plus"></i> Agregar Seleccionados
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
