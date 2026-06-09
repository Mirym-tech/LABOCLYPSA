<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1a1a1a;}
.header{background:#1e3a8a;color:white;padding:12px 20px;} .h1{font-size:18px;font-weight:bold;}
.pb{background:#dbeafe;border-bottom:2px solid #1e3a8a;padding:8px 20px;font-size:9.5px;}
.content{padding:12px 20px;} h2{font-size:11px;color:#1e3a8a;font-weight:bold;border-bottom:2px solid #1e3a8a;padding-bottom:3px;margin:12px 0 6px;}
.row{display:flex;gap:8px;margin-bottom:4px;font-size:10px;} .lbl{font-weight:bold;color:#1e40af;min-width:100px;}
.obs{border:1px solid #d1d5db;padding:6px;min-height:24px;font-size:9px;margin-bottom:6px;}
.footer{position:fixed;bottom:0;width:100%;border-top:1px solid #d1d5db;padding:4px 20px;font-size:8px;color:#6b7280;}
.firma{border-top:1px solid #374151;margin-top:30px;width:200px;text-align:center;padding-top:4px;font-size:9px;}
</style>
</head>
<body>
@php $r=$oa->resultadoCoprologia; $p=$oa->orden->paciente; @endphp
<div class="header"><div class="h1">LABOCLYPSA</div><div style="font-size:9px;opacity:.8">{{ $oa->orden->laboratorio->nombre }}</div><div style="float:right;margin-top:-30px;font-size:13px;font-weight:bold">Orden # {{ $oa->orden->numero_orden }}</div></div>
<div class="pb"><strong>Paciente:</strong> {{ $p->nombre }} | <strong>Fecha:</strong> {{ $oa->orden->fecha_entrada?->format('d/m/Y') }}</div>
<div class="content">
<h2>Coprológico — {{ $r->tipo_estudio ?? 'NORMAL' }}</h2>
<table style="width:100%;font-size:10px;margin-bottom:8px;">
<tr><td><strong>Color:</strong> {{ $r->color ?? '—' }}</td><td><strong>Consistencia:</strong> {{ $r->consistencia ?? '—' }}</td></tr>
</table>
@if($r->sin_parasitos)
<p style="font-weight:bold;border:1px solid #d1d5db;padding:6px;background:#f0fdf4;color:#166534;">✓ NO SE OBSERVAN ELEMENTOS PARASITARIOS EN ESTA MUESTRA</p>
@endif
@if($r->se_observan)
<p style="font-weight:bold;margin-top:6px">Se Observan:</p>
<div class="obs">{{ $r->se_observan }}</div>
@endif
<p><strong>Sangre Oculta:</strong> {{ $r->sangre_oculta ?? '—' }}</p>
@if($r->invest_amebas)
<p style="font-weight:bold;margin-top:6px">Invest. de Amebas:</p>
<div class="obs">{{ $r->invest_amebas }}</div>
@endif
@if($r->observacion)<p style="margin-top:6px;font-size:9px"><strong>Observación:</strong> {{ $r->observacion }}</p>@endif
<div class="firma">{{ $r->bioanalista?->name ?? '____________________________' }}<br>Bioanalista</div>
</div>
<div class="footer"><span>LABOCLYPSA</span><span style="float:right">Impreso: {{ now()->format('d/m/Y H:i') }}</span></div>
</body></html>
