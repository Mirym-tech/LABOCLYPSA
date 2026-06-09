<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1a1a1a;}
.header{background:#1e3a8a;color:white;padding:12px 20px;} .h1{font-size:18px;font-weight:bold;}
.pb{background:#dbeafe;border-bottom:2px solid #1e3a8a;padding:8px 20px;font-size:9.5px;}
.content{padding:12px 20px;} h2{font-size:11px;color:#1e3a8a;font-weight:bold;text-transform:uppercase;border-bottom:2px solid #1e3a8a;padding-bottom:3px;margin:12px 0 6px;}
table{width:100%;border-collapse:collapse;font-size:9.5px;} th{background:#eff6ff;color:#1e3a8a;padding:4px 8px;border:1px solid #bfdbfe;text-align:left;} td{padding:3px 8px;border:1px solid #e5e7eb;}
tr:nth-child(even) td{background:#f8fafc;}
.footer{position:fixed;bottom:0;width:100%;border-top:1px solid #d1d5db;padding:4px 20px;font-size:8px;color:#6b7280;}
.firma{border-top:1px solid #374151;margin-top:30px;width:200px;text-align:center;padding-top:4px;font-size:9px;}
</style>
</head>
<body>
@php $r=$oa->resultadoUroanalisis; $p=$oa->orden->paciente; @endphp
<div class="header"><div class="h1">LABOCLYPSA</div><div style="font-size:9px;opacity:.8">{{ $oa->orden->laboratorio->nombre }}</div><div style="float:right;margin-top:-30px;font-size:13px;font-weight:bold">Orden # {{ $oa->orden->numero_orden }}</div></div>
<div class="pb"><strong>Paciente:</strong> {{ $p->nombre }} | <strong>Edad:</strong> {{ $p->edad }} años | <strong>Sexo:</strong> {{ $p->sexo=='F'?'F':'M' }} | <strong>Fecha:</strong> {{ $oa->orden->fecha_entrada?->format('d/m/Y') }}</div>
<div class="content">
<h2>Examen General de Orina — Físico-Químico</h2>
<table>
<tr><th style="width:45%">Parámetro</th><th>Resultado</th></tr>
@foreach(['color'=>'Color','aspecto'=>'Aspecto','densidad'=>'Densidad','ph'=>'pH','glucosa'=>'Glucosa','proteina'=>'Proteína','acetona'=>'Acetona','bilirrubina'=>'Bilirrubina','urobilinogeno'=>'Urobilinógeno','sangre_oculta'=>'Sangre Oculta','hemoglobina'=>'Hemoglobina','nitrito'=>'Nitrito'] as $f=>$e)
<tr><td>{{ $e }}</td><td>{{ $r->$f ?? '—' }}</td></tr>
@endforeach
</table>

<h2>Segmento Urinario</h2>
<table>
<tr><th style="width:45%">Parámetro</th><th>Resultado</th></tr>
@foreach(['leucocitos'=>'Leucocitos','eritrocitos'=>'Eritrocitos','celulas_epiteliales'=>'Células Epiteliales','celulas_renales'=>'Células Renales','bacterias'=>'Bacterias','fibras_mucosas'=>'Fibras Mucosas','cristales'=>'Cristales','cilindros'=>'Cilindros','levaduras'=>'Levaduras','t_vaginalis'=>'T. Vaginalis'] as $f=>$e)
<tr><td>{{ $e }}</td><td>{{ $r->$f ?? '—' }}</td></tr>
@endforeach
</table>

<div class="firma">{{ $r->bioanalista?->name ?? '____________________________' }}<br>Bioanalista</div>
</div>
<div class="footer"><span>LABOCLYPSA</span><span style="float:right">Impreso: {{ now()->format('d/m/Y H:i') }}</span></div>
</body></html>
