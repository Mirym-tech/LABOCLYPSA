<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1a1a1a;}
.header{background:#1e3a8a;color:white;padding:12px 20px;} .header h1{font-size:18px;font-weight:bold;}
.pb{background:#dbeafe;border-bottom:2px solid #1e3a8a;padding:8px 20px;font-size:9.5px;}
.content{padding:12px 20px;} h2{font-size:11px;color:#1e3a8a;font-weight:bold;text-transform:uppercase;border-bottom:2px solid #1e3a8a;padding-bottom:3px;margin:12px 0 6px;}
table{width:100%;border-collapse:collapse;font-size:10px;} th{background:#eff6ff;color:#1e3a8a;padding:5px 10px;border:1px solid #bfdbfe;} td{padding:4px 10px;border:1px solid #e5e7eb;}
.footer{position:fixed;bottom:0;width:100%;border-top:1px solid #d1d5db;padding:4px 20px;font-size:8px;color:#6b7280;}
.firma{border-top:1px solid #374151;margin-top:30px;width:200px;text-align:center;padding-top:4px;font-size:9px;}
</style>
</head>
<body>
@php $r=$oa->resultadoColera; $p=$oa->orden->paciente; @endphp
<div class="header"><div class="h1">LABOCLYPSA</div><div style="font-size:9px;opacity:.8">{{ $oa->orden->laboratorio->nombre }}</div><div style="float:right;margin-top:-30px;font-size:13px;font-weight:bold">Orden # {{ $oa->orden->numero_orden }}</div></div>
<div class="pb"><strong>Paciente:</strong> {{ $p->nombre }} | <strong>Fecha:</strong> {{ $oa->orden->fecha_entrada?->format('d/m/Y') }} | <strong>Médico:</strong> {{ $p->medico_tratante ?? '—' }}</div>
<div class="content">
<h2>Análisis de Cólera</h2>
<table>
<tr><th>Parámetro</th><th>Resultado</th></tr>
<tr><td>Color</td><td>{{ $r->color ?? '—' }}</td></tr>
<tr><td>Consistencia</td><td>{{ $r->consistencia ?? '—' }}</td></tr>
<tr><td>Vibrio Cholerae (VC0-1)</td><td>{{ $r->vc01 ?? '—' }}</td></tr>
<tr><td>Vibrio Cholerae (VC01-1)</td><td>{{ $r->vc01_1 ?? '—' }}</td></tr>
<tr><td>Vibrio Cholerae (VC0-139)</td><td>{{ $r->vc0139 ?? '—' }}</td></tr>
</table>
@if($r->observacion)<p style="margin-top:10px;font-size:9px;"><strong>Observación:</strong> {{ $r->observacion }}</p>@endif
<div class="firma">{{ $r->bioanalista?->name ?? '____________________________' }}<br>Bioanalista</div>
</div>
<div class="footer"><span>LABOCLYPSA</span><span style="float:right">Impreso: {{ now()->format('d/m/Y H:i') }}</span></div>
</body></html>
