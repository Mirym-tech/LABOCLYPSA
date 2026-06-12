<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5px; color: #000; }
.header { display:table; width:100%; padding: 6px 10px 4px; border-bottom: 2px solid #000; }
.header-logo { display:table-cell; width:70px; vertical-align:middle; text-align:center; }
.logo-circle { border: 2px solid #1a3a8a; border-radius: 50%; width: 55px; height: 55px; line-height: 55px; text-align:center; font-size:22px; color:#1a3a8a; display:inline-block; }
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
</style>
</head>
<body>
@php
    $r = $oa->resultadoDigestion;
    $o = $oa->orden;
    $p = $o->paciente;
@endphp

<div class="header">
    <div class="header-logo">
        <img src="{{ 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('img/logo.png'))) }}" style="width:60px;height:60px;object-fit:contain;border-radius:6px;">
    </div>
    <div class="header-info">
        <div class="lab-name">LABOCLYPSA</div>
        <div class="lab-subtitle">Laboratorio Clínico Ysabel Pérez</div>
        <div class="lab-address">Agustín C. López # 22, Entrada Cerros Sabana Perdida, Santo Domingo Norte, R. D.</div>
        <div class="lab-address">Teléfonos: 809-234-3322 &nbsp; Rnc: 0011145916</div>
        <div class="lab-habilitacion">Habilitación No. A-02179</div>
    </div>
</div>

<div class="titulo">
    <div class="titulo-sub">RESULTADO ANÁLISIS CLÍNICO</div>
    <div class="titulo-tipo">DIGESTIÓN EN HECES</div>
</div>

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

<table style="width:calc(100% - 20px); margin:6px 10px; border-collapse:collapse; border:1px solid #000;">
<tr>
    <td style="width:50%; vertical-align:top; border-right:1px solid #000; padding:0;">
        <div class="section-header">Físico - Químico</div>
        <table style="width:100%; border-collapse:collapse;">
            @foreach(['color'=>'Color','olor'=>'Olor','consistencia'=>'Consistencia','alimentos_no_digeridos'=>'Alimentos No Digeridos','mucus'=>'Mucus','reaccion_ph'=>'Reacción (pH)','sangre_oculta'=>'Sangre Oculta','grasas'=>'Grasas','sustancia_reductora'=>'Sustancia Reductora','tripsina'=>'Tripsina'] as $f=>$e)
            <tr><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold; width:58%;">{{ $e }}</td><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold;">{{ strtoupper($r->$f ?? '') }}</td></tr>
            @endforeach
        </table>
    </td>
    <td style="width:50%; vertical-align:top; padding:0;">
        <div class="section-header">Examen Microscópico</div>
        <table style="width:100%; border-collapse:collapse;">
            @foreach(['leucocitos'=>'Leucocitos','eritrocitos'=>'Eritrocitos','celulas_epiteliales'=>'Células Epiteliales','fibras_mucosas'=>'Fibras Mucosas','cristales'=>'Cristales','bacterias'=>'Bacterias','huevos'=>'Huevos','parasitos'=>'Parásitos','quistes'=>'Quistes','granulos'=>'Gránulos','larvas'=>'Larvas','materiales_extranos'=>'Mat. Extraños'] as $f=>$e)
            <tr><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold; width:58%;">{{ $e }}</td><td style="padding:2px 6px; font-size:9px; border-bottom:1px solid #ddd; font-weight:bold;">{{ strtoupper($r->$f ?? '') }}</td></tr>
            @endforeach
        </table>
    </td>
</tr>
</table>

<div class="obs-box">
    <div class="obs-label">OBSERVACIÓN:</div>
    <div style="margin-top:4px;">{{ $r->observacion ?? '' }}</div>
</div>

<div class="firmas">
    <div class="firma-cell">
        <div class="firma-line">BIOANALISTA</div>
    </div>
    <div class="firma-cell">
        <div class="firma-line">DIRECTOR TÉCNICO</div>
    </div>
</div>

<div class="footer">
    <div class="footer-cell">Creó: {{ $r->bioanalista?->name ?? auth()->user()?->name }}</div>
    <div class="footer-cell-right">Imprimió: {{ auth()->user()?->name }} &nbsp; {{ now()->format('g:iA') }}</div>
</div>

</body>
</html>
