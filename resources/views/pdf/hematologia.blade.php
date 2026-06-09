<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a1a; }
    .header { background: #1e3a8a; color: white; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; }
    .header h1 { font-size: 18px; font-weight: bold; letter-spacing: 2px; }
    .header .sub { font-size: 9px; opacity: 0.8; margin-top: 2px; }
    .header .orden { text-align: right; font-size: 13px; font-weight: bold; }
    .paciente-bar { background: #dbeafe; border-bottom: 2px solid #1e3a8a; padding: 8px 20px; }
    .paciente-bar table { width: 100%; }
    .paciente-bar td { padding: 1px 4px; font-size: 9.5px; }
    .paciente-bar .label { color: #1e40af; font-weight: bold; }
    .content { padding: 12px 20px; }
    h2 { font-size: 11px; color: #1e3a8a; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
         border-bottom: 2px solid #1e3a8a; padding-bottom: 3px; margin: 12px 0 6px; }
    table.results { width: 100%; border-collapse: collapse; font-size: 9.5px; }
    table.results th { background: #eff6ff; color: #1e3a8a; font-weight: bold; padding: 4px 8px; border: 1px solid #bfdbfe; text-align: left; }
    table.results td { padding: 3px 8px; border: 1px solid #e5e7eb; }
    table.results tr:nth-child(even) td { background: #f8fafc; }
    .flag-high { color: #dc2626; font-weight: bold; }
    .flag-low  { color: #2563eb; font-weight: bold; }
    .obs { border: 1px solid #d1d5db; padding: 6px 10px; border-radius: 4px; min-height: 30px; font-size: 9px; }
    .footer { position: fixed; bottom: 0; width: 100%; border-top: 1px solid #d1d5db; padding: 6px 20px; font-size: 8px; color: #6b7280; display: flex; justify-content: space-between; }
    .firma { border-top: 1px solid #374151; margin-top: 30px; width: 200px; text-align: center; padding-top: 4px; font-size: 9px; }
</style>
</head>
<body>

{{-- Encabezado --}}
<div class="header">
    <div>
        <div class="h1">LABOCLYPSA</div>
        <div class="sub">{{ $oa->orden->laboratorio->nombre }} — {{ $oa->orden->laboratorio->direccion }}</div>
        <div class="sub">Tel: {{ $oa->orden->laboratorio->telefono }}</div>
    </div>
    <div class="orden">
        Orden # {{ $oa->orden->numero_orden }}<br>
        <span style="font-size:10px; font-weight:normal;">{{ $oa->orden->fecha_entrada?->format('d/m/Y') }}</span>
    </div>
</div>

{{-- Datos del paciente --}}
@php $p = $oa->orden->paciente; $r = $oa->resultadoHematologia; @endphp
<div class="paciente-bar">
    <table>
        <tr>
            <td><span class="label">Paciente:</span> {{ $p->nombre }}</td>
            <td><span class="label">Edad:</span> {{ $p->edad }} años</td>
            <td><span class="label">Sexo:</span> {{ $p->sexo == 'F' ? 'Femenino' : 'Masculino' }}</td>
            <td><span class="label">Cód:</span> {{ $p->codigo }}</td>
        </tr>
        <tr>
            <td><span class="label">Médico:</span> {{ $p->medico_tratante ?? '—' }}</td>
            <td><span class="label">Seguro:</span> {{ $p->seguro_medico ?? '—' }}</td>
            <td><span class="label">Factura:</span> {{ $oa->orden->numero_factura ?? '—' }}</td>
            <td><span class="label">Bioanalista:</span> {{ $r->bioanalista?->name ?? '—' }}</td>
        </tr>
    </table>
</div>

<div class="content">
    <h2>Hematología — Hemograma Completo (CBC)</h2>
    <table class="results">
        <tr><th>Parámetro</th><th>Resultado</th><th>Unidad</th><th>Valor de Referencia</th></tr>
        @php
        $params = [
            ['label'=>'WBC','field'=>'wbc','unit'=>'10³/UL','ref'=>'4.0 – 10.0'],
            ['label'=>'Lymph#','field'=>'lymph_abs','unit'=>'10³/UL','ref'=>'0.60 – 4.10'],
            ['label'=>'Mid#','field'=>'mid_abs','unit'=>'10³/UL','ref'=>'0.10 – 0.90'],
            ['label'=>'Gran#','field'=>'gran_abs','unit'=>'10³/UL','ref'=>'2.00 – 7.80'],
            ['label'=>'Lymph%','field'=>'lymph_pct','unit'=>'%','ref'=>'20.0 – 50.0'],
            ['label'=>'Mid%','field'=>'mid_pct','unit'=>'%','ref'=>'3.0 – 10.0'],
            ['label'=>'Gran%','field'=>'gran_pct','unit'=>'%','ref'=>'40.0 – 70.0'],
            ['label'=>'RBC','field'=>'rbc','unit'=>'10³/UL','ref'=>'3.80 – 5.80'],
            ['label'=>'HGB','field'=>'hgb','unit'=>'g/dL','ref'=>'11.0 – 16.5'],
            ['label'=>'HCT','field'=>'hct','unit'=>'%','ref'=>'35.0 – 50.0'],
            ['label'=>'MCV','field'=>'mcv','unit'=>'fL','ref'=>'80.0 – 100.0'],
            ['label'=>'MCH','field'=>'mch','unit'=>'pg','ref'=>'26.5 – 33.5'],
            ['label'=>'MCHC','field'=>'mchc','unit'=>'g/dL','ref'=>'32.2 – 36.0'],
            ['label'=>'RDW-CV','field'=>'rdw_cv','unit'=>'%','ref'=>'10.0 – 15.0'],
            ['label'=>'RDW-SD','field'=>'rdw_sd','unit'=>'fL','ref'=>'35.0 – 56.0'],
            ['label'=>'PLT','field'=>'plt','unit'=>'10³/UL','ref'=>'150 – 450'],
            ['label'=>'MPV','field'=>'mpv','unit'=>'fL','ref'=>'7.0 – 11.0'],
            ['label'=>'PDW','field'=>'pdw','unit'=>'%','ref'=>'10.0 – 18.0'],
            ['label'=>'PCT','field'=>'pct','unit'=>'%','ref'=>'0.100 – 0.500'],
            ['label'=>'P-LCR','field'=>'plcr','unit'=>'%','ref'=>'13.0 – 43.0'],
        ];
        @endphp
        @foreach($params as $p)
        <tr>
            <td><strong>{{ $p['label'] }}</strong></td>
            <td><strong>{{ $r->{$p['field']} ?? '—' }}</strong></td>
            <td>{{ $p['unit'] }}</td>
            <td style="color:#6b7280">{{ $p['ref'] }}</td>
        </tr>
        @endforeach
    </table>

    @if($r->observacion_general)
    <h2>Observación</h2>
    <div class="obs">{{ $r->observacion_general }}</div>
    @endif

    <table style="width:100%; margin-top:40px;">
        <tr>
            <td style="text-align:center">
                <div class="firma">
                    {{ $r->bioanalista?->name ?? '____________________________' }}<br>
                    Bioanalista
                </div>
            </td>
            <td style="text-align:right; font-size:8px; color:#9ca3af;">
                Impreso: {{ now()->format('d/m/Y H:i') }}<br>
                @if($r->validado) Validado: {{ $r->validado_at?->format('d/m/Y H:i') }} @endif
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    <span>LABOCLYPSA — Sistema de Laboratorio Clínico</span>
    <span>Confidencial — Solo para uso médico</span>
</div>

</body>
</html>
