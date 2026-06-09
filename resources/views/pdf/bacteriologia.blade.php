<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a1a; }
.header { background: #1e3a8a; color: white; padding: 12px 20px; }
.header h1 { font-size: 18px; font-weight: bold; } .header .sub { font-size: 9px; opacity:.8; }
.paciente-bar { background: #dbeafe; border-bottom: 2px solid #1e3a8a; padding: 8px 20px; font-size:9.5px; }
.content { padding: 12px 20px; }
h2 { font-size:11px; color:#1e3a8a; font-weight:bold; text-transform:uppercase; border-bottom:2px solid #1e3a8a; padding-bottom:3px; margin:12px 0 6px; }
table.ab { width:48%; border-collapse:collapse; font-size:9.5px; display:inline-table; margin-right:2%; }
table.ab th { background:#eff6ff; color:#1e3a8a; font-weight:bold; padding:4px 8px; border:1px solid #bfdbfe; }
table.ab td { padding:3px 8px; border:1px solid #e5e7eb; }
.S { color:#16a34a; font-weight:bold; } .R { color:#dc2626; font-weight:bold; } .I { color:#d97706; font-weight:bold; }
.obs { border:1px solid #d1d5db; padding:6px 10px; border-radius:4px; min-height:24px; font-size:9px; margin-top:6px; }
.firma { border-top:1px solid #374151; margin-top:30px; width:200px; text-align:center; padding-top:4px; font-size:9px; }
.footer { position:fixed; bottom:0; width:100%; border-top:1px solid #d1d5db; padding:4px 20px; font-size:8px; color:#6b7280; }
</style>
</head>
<body>
@php $r = $oa->resultadoBacteriologia; $p = $oa->orden->paciente; @endphp
<div class="header">
    <div class="h1">LABOCLYPSA</div>
    <div class="sub">{{ $oa->orden->laboratorio->nombre }} — {{ $oa->orden->laboratorio->direccion }}</div>
    <div style="float:right; margin-top:-30px; font-size:13px; font-weight:bold">Orden # {{ $oa->orden->numero_orden }}</div>
</div>
<div class="paciente-bar">
    <strong>Paciente:</strong> {{ $p->nombre }} &nbsp;|&nbsp; <strong>Edad:</strong> {{ $p->edad }} años &nbsp;|&nbsp;
    <strong>Médico:</strong> {{ $p->medico_tratante ?? '—' }} &nbsp;|&nbsp; <strong>Fecha:</strong> {{ $oa->orden->fecha_entrada?->format('d/m/Y') }}
</div>
<div class="content">
    <h2>Bacteriología — {{ $r->estudio ?? 'Cultivo' }}</h2>
    <table style="width:100%; font-size:9.5px; margin-bottom:8px;">
        <tr><td><strong>Muestra de:</strong> {{ $r->muestra_de ?? '—' }}</td><td><strong>Organismo:</strong> {{ $r->organismo ?? '—' }}</td></tr>
        <tr><td colspan="2"><strong>Aislado(s):</strong> {{ $r->aislados ?? '—' }}</td></tr>
    </table>

    <h2>Antibiograma</h2>
    @php
    $left  = ['penicilina','piperacilina','carbenicilina','ampicilina','amoxicilina','cefalexina','cefotaxina','tetraciclina','minociclina','eritrociclina','lincomicina','fosfocil','cefepime','ac_nalidixico','amox_ac_clav'];
    $right = ['norfloxacin','karamicina','gentamicina','tabramicina','amikacina','ceftriazona','cefazolin','levofloxacin','furadantoina','ciproflaxacina','clindamicina','sulfatrym','vancomicina','imipenen','cefunoxima'];
    @endphp
    <table class="ab">
        <tr><th>Antibiótico</th><th style="width:50px;text-align:center">S/R</th></tr>
        @foreach($left as $a)@if($r->$a)<tr><td>{{ strtoupper(str_replace('_',' ',$a)) }}</td><td class="{{ $r->$a }}" style="text-align:center">{{ $r->$a }}</td></tr>@endif@endforeach
    </table>
    <table class="ab">
        <tr><th>Antibiótico</th><th style="width:50px;text-align:center">S/R</th></tr>
        @foreach($right as $a)@if($r->$a)<tr><td>{{ strtoupper(str_replace('_',' ',$a)) }}</td><td class="{{ $r->$a }}" style="text-align:center">{{ $r->$a }}</td></tr>@endif@endforeach
    </table>

    @if($r->observacion)
    <h2>Observación</h2><div class="obs">{{ $r->observacion }}</div>
    @endif

    <div class="firma">{{ $r->bioanalista?->name ?? '____________________________' }}<br>Bioanalista</div>
</div>
<div class="footer"><span>LABOCLYPSA</span><span style="float:right">Impreso: {{ now()->format('d/m/Y H:i') }}</span></div>
</body>
</html>
