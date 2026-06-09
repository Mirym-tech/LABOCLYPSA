<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8">
<style>body{font-family:DejaVu Sans,sans-serif;font-size:10px;} .header{background:#1e3a8a;color:white;padding:12px 20px;} .h1{font-size:18px;font-weight:bold;} .pb{background:#dbeafe;padding:8px 20px;font-size:9.5px;border-bottom:2px solid #1e3a8a;} .content{padding:12px 20px;} h2{font-size:11px;color:#1e3a8a;font-weight:bold;border-bottom:2px solid #1e3a8a;padding-bottom:3px;margin:10px 0 6px;} table{width:100%;border-collapse:collapse;font-size:9.5px;} th{background:#eff6ff;color:#1e3a8a;padding:4px 8px;border:1px solid #bfdbfe;text-align:left;} td{padding:4px 8px;border:1px solid #e5e7eb;} .footer{position:fixed;bottom:0;width:100%;border-top:1px solid #d1d5db;padding:4px 20px;font-size:8px;color:#6b7280;} .firma{border-top:1px solid #374151;margin-top:30px;width:200px;text-align:center;padding-top:4px;font-size:9px;}</style>
</head><body>
@php $resultados=$oa->resultadoVarios; $p=$oa->orden->paciente; @endphp
<div class="header"><div class="h1">LABOCLYPSA</div><div style="font-size:9px;opacity:.8">{{ $oa->orden->laboratorio->nombre }}</div></div>
<div class="pb"><strong>Paciente:</strong> {{ $p->nombre }} | <strong>Fecha:</strong> {{ $oa->orden->fecha_entrada?->format('d/m/Y') }} | Orden # {{ $oa->orden->numero_orden }}</div>
<div class="content">
<h2>Análisis Varios</h2>
<table><tr><th>Análisis</th><th>Resultado</th><th>Valor Ref.</th><th>Medidas</th><th>Método</th></tr>
@foreach($resultados as $rv)
<tr><td><strong>{{ $rv->sub_grupo }}</strong><br><span style="font-size:8px;color:#6b7280">{{ $rv->grupo }}</span></td><td>{{ $rv->resultado }}</td><td>{{ $rv->valor_ref ?? '—' }}</td><td>{{ $rv->medidas ?? '—' }}</td><td>{{ $rv->metodo ?? '—' }}</td></tr>
@endforeach</table>
<div class="firma">Bioanalista</div>
</div>
<div class="footer"><span>LABOCLYPSA</span><span style="float:right">Impreso: {{ now()->format('d/m/Y H:i') }}</span></div>
</body></html>
