<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5px; color: #000; }
.header { display:table; width:100%; padding: 6px 10px 4px; border-bottom: 2px solid #000; }
.header-logo { display:table-cell; width:70px; vertical-align:middle; text-align:center; }
.header-info { display:table-cell; vertical-align:middle; padding-left:8px; }
.lab-name { font-size:18px; font-weight:bold; color:#000; letter-spacing:1px; }
.lab-subtitle { font-size:11px; font-weight:bold; color:#1a3a8a; text-transform:uppercase; }
.lab-address { font-size:8px; color:#333; margin-top:2px; }
.lab-habilitacion { font-size:8.5px; color:#cc0000; font-weight:bold; margin-top:2px; }
.titulo { text-align:center; margin: 8px 10px 4px; }
.titulo-sub { font-size:10px; font-weight:bold; letter-spacing:1px; }
.titulo-tipo { font-size:14px; font-weight:bold; letter-spacing:3px; text-decoration:underline; margin-top:2px; }
.datos-paciente { margin: 6px 10px; border: 1px solid #000; }
.datos-row { display:table; width:100%; border-bottom: 1px solid #999; }
.datos-row:last-child { border-bottom: none; }
.datos-cell { display:table-cell; padding: 2px 6px; width:50%; font-size:9px; }
.datos-cell-right { display:table-cell; padding: 2px 6px; width:50%; font-size:9px; border-left: 1px solid #999; }
.datos-valor { font-weight:bold; }
.section-header { background:#e8e8e8; font-weight:bold; font-size:8.5px; text-align:center; padding:2px; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #000; }
.obs-box { margin: 6px 10px; border: 1px solid #000; padding: 4px 8px; min-height: 35px; font-size:9px; }
.obs-label { font-weight:bold; margin-bottom:2px; }
.firmas { margin: 10px 10px 4px; display:table; width: calc(100% - 20px); }
.firma-cell { display:table-cell; width:50%; padding: 0 10px; }
.firma-line { border-top: 1px solid #000; margin-top: 25px; padding-top: 3px; font-size:9px; font-weight:bold; text-align:center; text-transform:uppercase; }
.footer { margin: 6px 10px 0; border-top: 1px solid #ccc; padding-top: 3px; font-size:8px; display:table; width: calc(100% - 20px); }
.footer-cell { display:table-cell; width:50%; }
.footer-cell-right { display:table-cell; width:50%; text-align:right; }
/* Hematología */
.res-table { width:calc(100% - 20px); border-collapse:collapse; margin:0 10px; }
.res-table th { background:#e0e0e0; font-size:8.5px; font-weight:bold; padding:2px 6px; border:1px solid #999; text-align:left; }
.res-table td { padding:2px 6px; font-size:9px; border:1px solid #ddd; }
.res-table td:first-child { font-weight:bold; width:38%; }
/* Bacteriología */
.S { color:#166534; font-weight:bold; }
.R { color:#dc2626; font-weight:bold; }
.I { color:#d97706; font-weight:bold; }
/* Uroanálisis */
.uro-table { width:100%; border-collapse:collapse; }
.uro-table tr td { padding: 2px 6px; font-size:9px; border-bottom: 1px solid #ddd; }
.uro-table tr td:first-child { width:55%; font-weight:bold; text-transform:uppercase; }
.uro-table tr td:last-child { font-weight:bold; text-transform:uppercase; }
/* Salto de página */
.page-break { page-break-after: always; }
</style>
</head>
<body>
@php
    $o      = $orden;
    $p      = $orden->paciente;
    $logo   = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png')));
    $listos = $orden->analisis->filter(fn($oa) => $oa->estado === 'listo')->values();
@endphp

@foreach($listos as $oa)
@php
    $cat      = $oa->tipo->categoria;
    $nomLow   = strtolower($oa->tipo->nombre ?? '');
    $esCopro  = str_contains($nomLow, 'coprol');
    $creador  = auth()->user()?->name;
@endphp

{{-- ═══ ENCABEZADO ═══ --}}
<div class="header">
    <div class="header-logo">
        <img src="{{ $logo }}" style="width:60px;height:60px;object-fit:contain;border-radius:6px;">
    </div>
    <div class="header-info">
        <div class="lab-name">LABOCLYPSA</div>
        <div class="lab-subtitle">Laboratorio Clínico Ysabel Pérez</div>
        <div class="lab-address">Agustín C. López # 22, Entrada Cerros Sabana Perdida, Santo Domingo Norte, R. D.</div>
        <div class="lab-address">Teléfonos: 809-234-3322 &nbsp; Rnc: 0011145916</div>
        <div class="lab-habilitacion">Habilitación No. A-02179</div>
    </div>
</div>

{{-- ═══ TÍTULO ═══ --}}
<div class="titulo">
    <div class="titulo-sub">RESULTADO ANÁLISIS CLÍNICO</div>
    <div class="titulo-tipo">
        @if($cat === 'HEMATOLOGIA' || $cat === 'HEMATO/COAGULACION')HEMATOLOGÍA
        @elseif($cat === 'BACTERIOLOGIA')BACTERIOLOGÍA
        @elseif($cat === 'ANALISIS DE COLERA')ANÁLISIS DE CÓLERA
        @elseif($cat === 'UROANALISIS' && $esCopro)COPROLÓGICO
        @elseif($cat === 'UROANALISIS')EXAMEN &nbsp; ORINA
        @elseif($cat === 'DIGESTION EN HECES')DIGESTIÓN EN HECES
        @elseif($cat === 'ANALISIS VARIOS')ANÁLISIS VARIOS
        @else{{ strtoupper($oa->tipo->nombre) }}
        @endif
    </div>
</div>

{{-- ═══ DATOS DEL PACIENTE ═══ --}}
<div class="datos-paciente">
    <div class="datos-row">
        <div class="datos-cell">Paciente : <span class="datos-valor">{{ strtoupper($p->nombre ?? '') }}</span></div>
        <div class="datos-cell-right">Seguro : <span class="datos-valor">{{ $p->seguro_medico ?? '' }}</span></div>
    </div>
    <div class="datos-row">
        <div class="datos-cell">Dirección : <span class="datos-valor">{{ $p->direccion ?? '' }}</span></div>
        <div class="datos-cell-right">Médico : <span class="datos-valor">{{ $p->medico_tratante ?? '' }}</span></div>
    </div>
    <div class="datos-row">
        <div class="datos-cell">Teléfono : <span class="datos-valor">{{ $p->telefono ?? '000-000-0000' }}</span></div>
        <div class="datos-cell-right">Tipo : <span class="datos-valor">{{ ucfirst($o->tipo_paciente) }}</span> &nbsp;&nbsp; No. Registro: <span class="datos-valor">{{ $o->numero_orden }}</span></div>
    </div>
    <div class="datos-row">
        <div class="datos-cell">Edad : <span class="datos-valor">{{ $p->edad ?? '' }}</span> &nbsp;&nbsp;&nbsp; LABORATORIO No. <span class="datos-valor">{{ $o->numero_entrada }}</span></div>
        <div class="datos-cell-right">Fecha: <span class="datos-valor">{{ $o->fecha_entrada?->format('d-m-Y') }}</span></div>
    </div>
</div>

{{-- ═══ CONTENIDO POR CATEGORÍA ═══ --}}

@if($cat === 'HEMATOLOGIA' || $cat === 'HEMATO/COAGULACION')
@php
    $r = $oa->resultadoHematologia;
    $creador = $r?->bioanalista?->name ?? $creador;
    $params = [
        ['label'=>'WBC',    'field'=>'wbc',     'unit'=>'10³/UL', 'ref'=>'4.0 – 10.0'],
        ['label'=>'Lymph#', 'field'=>'lymph_abs','unit'=>'10³/UL', 'ref'=>'0.60 – 4.10'],
        ['label'=>'Mid#',   'field'=>'mid_abs',  'unit'=>'10³/UL', 'ref'=>'0.10 – 0.90'],
        ['label'=>'Gran#',  'field'=>'gran_abs', 'unit'=>'10³/UL', 'ref'=>'2.00 – 7.80'],
        ['label'=>'Lymph%', 'field'=>'lymph_pct','unit'=>'%',      'ref'=>'20.0 – 50.0'],
        ['label'=>'Mid%',   'field'=>'mid_pct',  'unit'=>'%',      'ref'=>'3.0 – 10.0'],
        ['label'=>'Gran%',  'field'=>'gran_pct', 'unit'=>'%',      'ref'=>'40.0 – 70.0'],
        ['label'=>'RBC',    'field'=>'rbc',      'unit'=>'10⁶/UL', 'ref'=>'3.80 – 5.80'],
        ['label'=>'HGB',    'field'=>'hgb',      'unit'=>'g/dL',   'ref'=>'11.0 – 16.5'],
        ['label'=>'HCT',    'field'=>'hct',      'unit'=>'%',      'ref'=>'35.0 – 50.0'],
        ['label'=>'MCV',    'field'=>'mcv',      'unit'=>'fL',     'ref'=>'80.0 – 100.0'],
        ['label'=>'MCH',    'field'=>'mch',      'unit'=>'pg',     'ref'=>'26.5 – 33.5'],
        ['label'=>'MCHC',   'field'=>'mchc',     'unit'=>'g/dL',   'ref'=>'32.2 – 36.0'],
        ['label'=>'RDW-CV', 'field'=>'rdw_cv',   'unit'=>'%',      'ref'=>'10.0 – 15.0'],
        ['label'=>'RDW-SD', 'field'=>'rdw_sd',   'unit'=>'fL',     'ref'=>'35.0 – 56.0'],
        ['label'=>'PLT',    'field'=>'plt',      'unit'=>'10³/UL', 'ref'=>'150 – 450'],
        ['label'=>'MPV',    'field'=>'mpv',      'unit'=>'fL',     'ref'=>'7.0 – 11.0'],
        ['label'=>'PDW',    'field'=>'pdw',      'unit'=>'%',      'ref'=>'10.0 – 18.0'],
        ['label'=>'PCT',    'field'=>'pct',      'unit'=>'%',      'ref'=>'0.100 – 0.500'],
        ['label'=>'P-LCR',  'field'=>'plcr',     'unit'=>'%',      'ref'=>'13.0 – 43.0'],
    ];
@endphp
<div class="section-header" style="margin: 4px 10px 0;">Hemograma Completo (CBC)</div>
<table class="res-table" style="border:1px solid #000;">
    <tr><th>Parámetro</th><th>Resultado</th><th>Unidad</th><th>Valor de Referencia</th></tr>
    @foreach($params as $param)
    <tr>
        <td>{{ $param['label'] }}</td>
        <td>{{ $r->{$param['field']} ?? '—' }}</td>
        <td style="color:#555">{{ $param['unit'] }}</td>
        <td style="color:#777">{{ $param['ref'] }}</td>
    </tr>
    @endforeach
</table>
<div class="obs-box" style="margin-top:6px;">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observacion_general ?? '' }}</div>
</div>

@elseif($cat === 'BACTERIOLOGIA')
@php
    $r = $oa->resultadoBacteriologia;
    $creador = $r?->bioanalista?->name ?? $creador;
    $abLeft  = ['penicilina','piperacilina','carbenicilina','ampicilina','amoxicilina','cefalexina','cefotaxina','tetraciclina','minociclina','eritrociclina','lincomicina','fosfocil','cefepime','ac_nalidixico','amox_ac_clav'];
    $abRight = ['norfloxacin','karamicina','gentamicina','tabramicina','amikacina','ceftriazona','cefazolin','levofloxacin','furadantoina','ciproflaxacina','clindamicina','sulfatrym','vancomicina','imipenen','cefunoxima'];
    $hasLeft  = collect($abLeft)->some(fn($a) => !empty($r?->$a));
    $hasRight = collect($abRight)->some(fn($a) => !empty($r?->$a));
@endphp
<table style="width:calc(100% - 20px); margin:6px 10px; border-collapse:collapse; border:1px solid #000;">
    <tr>
        <td style="padding:3px 8px; width:50%; border-right:1px solid #999; font-size:9px;"><strong>Muestra de:</strong> {{ $r?->muestra_de ?? '—' }}</td>
        <td style="padding:3px 8px; width:50%; font-size:9px;"><strong>Estudio:</strong> {{ $r?->estudio ?? '—' }}</td>
    </tr>
    <tr><td colspan="2" style="padding:3px 8px; font-size:9px; border-top:1px solid #999;"><strong>Organismo:</strong> {{ $r?->organismo ?? '—' }}</td></tr>
    <tr><td colspan="2" style="padding:3px 8px; font-size:9px; border-top:1px solid #999;"><strong>Aislado(s):</strong> {{ $r?->aislados ?? '—' }}</td></tr>
</table>
@if($hasLeft || $hasRight)
<div class="section-header" style="margin: 4px 10px 0;">Antibiograma</div>
<table style="width:calc(100% - 20px); margin:0 10px; border-collapse:collapse; border:1px solid #000;">
<tr>
    <td style="width:50%; vertical-align:top; border-right:1px solid #000; padding:0;">
        <table style="width:100%; border-collapse:collapse;">
            <tr><th style="background:#e8e8e8; padding:2px 6px; font-size:8.5px; border-bottom:1px solid #999; text-align:left;">Antibiótico</th><th style="background:#e8e8e8; width:40px; text-align:center; font-size:8.5px; border-bottom:1px solid #999; padding:2px 4px;">S/R</th></tr>
            @foreach($abLeft as $a) @if(!empty($r?->$a))
            <tr><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #eee;">{{ strtoupper(str_replace('_',' ',$a)) }}</td><td class="{{ $r->$a }}" style="text-align:center; padding:2px 4px; font-size:9px; border-bottom:1px solid #eee;">{{ $r->$a }}</td></tr>
            @endif @endforeach
        </table>
    </td>
    <td style="width:50%; vertical-align:top; padding:0;">
        <table style="width:100%; border-collapse:collapse;">
            <tr><th style="background:#e8e8e8; padding:2px 6px; font-size:8.5px; border-bottom:1px solid #999; text-align:left;">Antibiótico</th><th style="background:#e8e8e8; width:40px; text-align:center; font-size:8.5px; border-bottom:1px solid #999; padding:2px 4px;">S/R</th></tr>
            @foreach($abRight as $a) @if(!empty($r?->$a))
            <tr><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #eee;">{{ strtoupper(str_replace('_',' ',$a)) }}</td><td class="{{ $r->$a }}" style="text-align:center; padding:2px 4px; font-size:9px; border-bottom:1px solid #eee;">{{ $r->$a }}</td></tr>
            @endif @endforeach
        </table>
    </td>
</tr>
</table>
@endif
<div class="obs-box" style="margin-top:6px;">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observacion ?? '' }}</div>
</div>

@elseif($cat === 'ANTIGENOS' || $cat === 'SEROLOGIA')
@php
    $r = $oa->resultadoSerologia;
    $creador = $r?->bioanalista?->name ?? $creador;
    $parametros = [
        'salmonella_o_a'      => 'Salmonella O Grupo A',
        'salmonella_o_b'      => 'Salmonella O Grupo B',
        'salmonella_o_c'      => 'Salmonella O Grupo C',
        'salmonella_o_d'      => 'Salmonella O Grupo D',
        'salmonella_h_a'      => 'Salmonella H Grupo A',
        'salmonella_h_b'      => 'Salmonella H Grupo B',
        'salmonella_h_c'      => 'Salmonella H Grupo C',
        'salmonella_h_d'      => 'Salmonella H Grupo D',
        'proteus_ox2'         => 'Proteus OX 2',
        'proteus_ox19'        => 'Proteus OX 19',
        'proteus_oxk'         => 'Proteus OX K',
        'brucella_abortus'    => 'Brucella Abortus',
        'typhoide_o_somatica' => 'Typhoide O Somática',
    ];
@endphp
<div class="section-header" style="margin: 4px 10px 0;">Resultados</div>
<table style="width:calc(100% - 20px); margin:0 10px; border-collapse:collapse; border:1px solid #000; border-top:none;">
    <tr style="background:#f5f5f5;">
        <th style="padding:2px 6px; font-size:8.5px; text-align:left; border-bottom:1px solid #999; border-right:1px solid #999; width:55%;">Parámetro</th>
        <th style="padding:2px 6px; font-size:8.5px; text-align:center; border-bottom:1px solid #999;">Resultado</th>
    </tr>
    @foreach($parametros as $campo => $etiqueta)
    <tr>
        <td style="padding:2px 8px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999; font-weight:bold;">{{ $etiqueta }}</td>
        <td style="padding:2px 8px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold; text-align:center;">{{ strtoupper($r?->$campo ?? 'NEGATIVO') }}</td>
    </tr>
    @endforeach
</table>
<div class="obs-box" style="margin-top:6px;">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observacion ?? '' }}</div>
</div>

@elseif($cat === 'ANALISIS DE COLERA')
@php
    $r = $oa->resultadoColera;
    $creador = $r?->bioanalista?->name ?? $creador;
@endphp
<table style="width:calc(100% - 20px); margin:6px 10px; border-collapse:collapse; border:1px solid #000;">
    <tr style="background:#e8e8e8;">
        <th style="padding:2px 6px; font-size:8.5px; text-align:left; border-bottom:1px solid #999; border-right:1px solid #999;">Parámetro</th>
        <th style="padding:2px 6px; font-size:8.5px; text-align:center; border-bottom:1px solid #999;">Resultado</th>
    </tr>
    <tr><td style="padding:3px 8px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999;">Color</td><td style="padding:3px 8px; font-size:9px; font-weight:bold; text-align:center; border-bottom:1px solid #ddd;">{{ strtoupper($r?->color ?? '—') }}</td></tr>
    <tr><td style="padding:3px 8px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999;">Consistencia</td><td style="padding:3px 8px; font-size:9px; font-weight:bold; text-align:center; border-bottom:1px solid #ddd;">{{ strtoupper($r?->consistencia ?? '—') }}</td></tr>
    <tr><td style="padding:3px 8px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999;">Vibrio Cholerae (VC0-1)</td><td style="padding:3px 8px; font-size:9px; font-weight:bold; text-align:center; border-bottom:1px solid #ddd;">{{ strtoupper($r?->vc01 ?? '—') }}</td></tr>
    <tr><td style="padding:3px 8px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999;">Vibrio Cholerae (VC01-1)</td><td style="padding:3px 8px; font-size:9px; font-weight:bold; text-align:center; border-bottom:1px solid #ddd;">{{ strtoupper($r?->vc01_1 ?? '—') }}</td></tr>
    <tr><td style="padding:3px 8px; font-size:9px; border-right:1px solid #999;">Vibrio Cholerae (VC0-139)</td><td style="padding:3px 8px; font-size:9px; font-weight:bold; text-align:center;">{{ strtoupper($r?->vc0139 ?? '—') }}</td></tr>
</table>
<div class="obs-box" style="margin-top:6px;">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observacion ?? '' }}</div>
</div>

@elseif($cat === 'UROANALISIS' && !$esCopro)
@php
    $r = $oa->resultadoUroanalisis;
    $creador = $r?->bioanalista?->name ?? $creador;
@endphp
<table style="width:calc(100% - 20px); margin:4px 10px; border-collapse:collapse; border:1px solid #000;">
<tr>
    <td style="width:50%; vertical-align:top; border-right:1px solid #000; padding:0;">
        <div class="section-header">Físico - Químico</div>
        <table class="uro-table">
            <tr><td>Color</td><td>{{ strtoupper($r?->color ?? '') }}</td></tr>
            <tr><td>Aspecto</td><td>{{ strtoupper($r?->aspecto ?? '') }}</td></tr>
            <tr><td>Densidad</td><td>{{ $r?->densidad ?? '' }}</td></tr>
            <tr><td>PH:</td><td>{{ $r?->ph ?? '' }}</td></tr>
            <tr><td>Glucosa</td><td>{{ strtoupper($r?->glucosa ?? '') }}</td></tr>
            <tr><td>Proteína</td><td>{{ strtoupper($r?->proteina ?? '') }}</td></tr>
            <tr><td>Acetona</td><td>{{ strtoupper($r?->acetona ?? '') }}</td></tr>
            <tr><td>Bilirrubina</td><td>{{ strtoupper($r?->bilirrubina ?? '') }}</td></tr>
            <tr><td>Urobilinógeno</td><td>{{ strtoupper($r?->urobilinogeno ?? '') }}</td></tr>
            <tr><td>Sangre Oculta</td><td>{{ strtoupper($r?->sangre_oculta ?? '') }}</td></tr>
            <tr><td>Hemoglobina</td><td>{{ strtoupper($r?->hemoglobina ?? '') }}</td></tr>
            <tr><td>Nitrito</td><td><em>{{ strtoupper($r?->nitrito ?? '') }}</em></td></tr>
        </table>
    </td>
    <td style="width:50%; vertical-align:top; padding:0;">
        <div class="section-header">Examen Microscópico</div>
        <table class="uro-table">
            <tr><td>Leucocitos</td><td>{{ $r?->leucocitos ?? '' }}</td></tr>
            <tr><td>Eritrocitos</td><td>{{ $r?->eritrocitos ?? '' }}</td></tr>
            <tr><td>Células Epiteliales</td><td>{{ strtoupper($r?->celulas_epiteliales ?? '') }}</td></tr>
            <tr><td>Células Renales</td><td>{{ strtoupper($r?->celulas_renales ?? '') }}</td></tr>
            <tr><td>Bacterias</td><td>{{ strtoupper($r?->bacterias ?? '') }}</td></tr>
            <tr><td>Fibras Mucosas</td><td>{{ strtoupper($r?->fibras_mucosas ?? '') }}</td></tr>
            <tr><td>Cristales</td><td>{{ strtoupper($r?->cristales ?? '') }}</td></tr>
            <tr><td>Cilindros</td><td>{{ strtoupper($r?->cilindros ?? '') }}</td></tr>
            <tr><td>Levaduras</td><td>{{ strtoupper($r?->levaduras ?? '') }}</td></tr>
            <tr><td>T. Vaginalis</td><td>{{ strtoupper($r?->t_vaginalis ?? '') }}</td></tr>
        </table>
    </td>
</tr>
</table>
<div class="obs-box">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observaciones ?? '' }}</div>
</div>

@elseif($cat === 'UROANALISIS' && $esCopro)
@php
    $r = $oa->resultadoCoprologia;
    $creador = $r?->bioanalista?->name ?? $creador;
@endphp
<div class="section-header" style="margin: 4px 10px 0;">Tipo de Estudio: {{ strtoupper($r?->tipo_estudio ?? 'Normal') }}</div>
<table style="width:calc(100% - 20px); margin:0 10px; border-collapse:collapse; border:1px solid #000;">
    <tr>
        <td style="padding:3px 8px; width:50%; border-right:1px solid #999; font-size:9px;"><strong>Color:</strong> {{ strtoupper($r?->color ?? '—') }}</td>
        <td style="padding:3px 8px; width:50%; font-size:9px;"><strong>Consistencia:</strong> {{ strtoupper($r?->consistencia ?? '—') }}</td>
    </tr>
    <tr>
        <td style="padding:3px 8px; border-top:1px solid #ddd; border-right:1px solid #999; font-size:9px;"><strong>Sangre Oculta:</strong> {{ strtoupper($r?->sangre_oculta ?? '—') }}</td>
        <td style="padding:3px 8px; border-top:1px solid #ddd; font-size:9px;"></td>
    </tr>
</table>
@if($r?->sin_parasitos)
<div style="margin:4px 10px; padding:4px 8px; border:1px solid #000; background:#f0fdf4; font-size:9px; font-weight:bold;">
    NO SE OBSERVAN ELEMENTOS PARASITARIOS EN ESTA MUESTRA
</div>
@endif
@if($r?->se_observan)
<div class="obs-box" style="margin-top:4px;">
    <div class="obs-label">SE OBSERVAN:</div>
    <div style="margin-top:4px;">{{ $r->se_observan }}</div>
</div>
@endif
@if($r?->invest_amebas)
<div class="obs-box" style="margin-top:4px;">
    <div class="obs-label">INVEST. DE AMEBAS:</div>
    <div style="margin-top:4px;">{{ $r->invest_amebas }}</div>
</div>
@endif
<div class="obs-box" style="margin-top:4px;">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observacion ?? '' }}</div>
</div>

@elseif($cat === 'DIGESTION EN HECES')
@php
    $r = $oa->resultadoDigestion;
    $creador = $r?->bioanalista?->name ?? $creador;
@endphp
<table style="width:calc(100% - 20px); margin:6px 10px; border-collapse:collapse; border:1px solid #000;">
<tr>
    <td style="width:50%; vertical-align:top; border-right:1px solid #000; padding:0;">
        <div class="section-header">Físico - Químico</div>
        <table style="width:100%; border-collapse:collapse;">
            @foreach(['color'=>'Color','olor'=>'Olor','consistencia'=>'Consistencia','alimentos_no_digeridos'=>'Alimentos No Digeridos','mucus'=>'Mucus','reaccion_ph'=>'Reacción (pH)','sangre_oculta'=>'Sangre Oculta','grasas'=>'Grasas','sustancia_reductora'=>'Sustancia Reductora','tripsina'=>'Tripsina'] as $f=>$e)
            <tr><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold; width:58%;">{{ $e }}</td><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold;">{{ strtoupper($r?->$f ?? '') }}</td></tr>
            @endforeach
        </table>
    </td>
    <td style="width:50%; vertical-align:top; padding:0;">
        <div class="section-header">Examen Microscópico</div>
        <table style="width:100%; border-collapse:collapse;">
            @foreach(['leucocitos'=>'Leucocitos','eritrocitos'=>'Eritrocitos','celulas_epiteliales'=>'Células Epiteliales','fibras_mucosas'=>'Fibras Mucosas','cristales'=>'Cristales','bacterias'=>'Bacterias','huevos'=>'Huevos','parasitos'=>'Parásitos','quistes'=>'Quistes','granulos'=>'Gránulos','larvas'=>'Larvas','materiales_extranos'=>'Mat. Extraños'] as $f=>$e)
            <tr><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold; width:58%;">{{ $e }}</td><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold;">{{ strtoupper($r?->$f ?? '') }}</td></tr>
            @endforeach
        </table>
    </td>
</tr>
</table>
<div class="obs-box">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r?->observacion ?? '' }}</div>
</div>

@elseif($cat === 'ANALISIS VARIOS')
@php
    $rvList  = $oa->resultadoVarios;
    $grupos  = $rvList->groupBy('grupo');
    $creador = $rvList->first()?->bioanalista?->name ?? $creador;
@endphp
@foreach($grupos as $grupo => $items)
<div class="section-header" style="margin: 4px 10px 0;">{{ strtoupper($grupo ?: 'Resultados') }}</div>
<table style="width:calc(100% - 20px); margin:0 10px; border-collapse:collapse; border:1px solid #000; border-top:none;">
    <tr style="background:#f5f5f5;">
        <th style="padding:2px 6px; font-size:8.5px; text-align:left; border-bottom:1px solid #999; border-right:1px solid #999;">Análisis</th>
        <th style="padding:2px 6px; font-size:8.5px; text-align:center; border-bottom:1px solid #999; border-right:1px solid #999; width:20%;">Resultado</th>
        <th style="padding:2px 6px; font-size:8.5px; text-align:center; border-bottom:1px solid #999; border-right:1px solid #999; width:20%;">Valor Ref.</th>
        <th style="padding:2px 6px; font-size:8.5px; text-align:center; border-bottom:1px solid #999; width:15%;">Unidad</th>
    </tr>
    @foreach($items as $rv)
    <tr>
        <td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999; font-weight:bold;">{{ $rv->sub_grupo }}</td>
        <td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999; font-weight:bold; text-align:center;">{{ $rv->resultado ?? '' }}</td>
        <td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; border-right:1px solid #999; text-align:center; color:#555;">{{ $rv->valor_ref ?? '' }}</td>
        <td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; text-align:center; color:#555;">{{ $rv->medidas ?? '' }}</td>
    </tr>
    @endforeach
</table>
@endforeach
<div class="obs-box" style="margin-top:6px;">
    <div class="obs-label">OBSERVACIÓN:</div>
</div>

@else
{{-- Tipo sin plantilla PDF --}}
<div style="margin: 20px 10px; text-align:center; font-size:11px; color:#777;">
    {{ $oa->tipo->nombre }}
</div>
@endif

{{-- ═══ FIRMAS ═══ --}}
<div class="firmas">
    <div class="firma-cell"><div class="firma-line">BIOANALISTA</div></div>
    <div class="firma-cell"><div class="firma-line">DIRECTOR TÉCNICO</div></div>
</div>

{{-- ═══ FOOTER ═══ --}}
<div class="footer">
    <div class="footer-cell">Creó: {{ $creador }}</div>
    <div class="footer-cell-right">Imprimió: {{ auth()->user()?->name }} &nbsp; {{ now()->format('g:iA') }}</div>
</div>

{{-- Salto de página entre resultados --}}
@unless($loop->last)
<div class="page-break"></div>
@endunless

@endforeach
</body>
</html>
