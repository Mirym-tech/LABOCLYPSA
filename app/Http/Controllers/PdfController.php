<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\OrdenAnalisis;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    private function verificarAcceso(OrdenAnalisis $oa): void
    {
        // Ambas sedes comparten acceso completo a todos los resultados.
        // La autenticación ya está garantizada por el middleware 'auth' en las rutas.
    }

    public function hematologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoHematologia.bioanalista']);
        $pdf = Pdf::loadView('pdf.hematologia', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('hematologia_' . $oa->orden->numero_orden . '.pdf');
    }

    public function bacteriologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoBacteriologia.bioanalista']);
        $pdf = Pdf::loadView('pdf.bacteriologia', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('bacteriologia_' . $oa->orden->numero_orden . '.pdf');
    }

    public function colera(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoColera.bioanalista']);
        $pdf = Pdf::loadView('pdf.colera', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('colera_' . $oa->orden->numero_orden . '.pdf');
    }

    public function uroanalisis(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoUroanalisis.bioanalista']);
        $pdf = Pdf::loadView('pdf.uroanalisis', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('uroanalisis_' . $oa->orden->numero_orden . '.pdf');
    }

    public function coprologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoCoprologia.bioanalista']);
        $pdf = Pdf::loadView('pdf.coprologia', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('coprologia_' . $oa->orden->numero_orden . '.pdf');
    }

    public function serologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoSerologia.bioanalista']);
        $pdf = Pdf::loadView('pdf.serologia', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('serologia_' . $oa->orden->numero_orden . '.pdf');
    }

    public function digestion(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoDigestion.bioanalista']);
        $pdf = Pdf::loadView('pdf.digestion', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('digestion_' . $oa->orden->numero_orden . '.pdf');
    }

    public function varios(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load(['orden.paciente', 'orden.laboratorio', 'resultadoVarios.bioanalista']);
        $pdf = Pdf::loadView('pdf.varios', compact('oa'))->setPaper('letter', 'portrait');
        return $pdf->stream('varios_' . $oa->orden->numero_orden . '.pdf');
    }

    public function ordenCompleta(Orden $orden)
    {
        $orden->load([
            'paciente', 'laboratorio',
            'analisis.tipo',
            'analisis.resultadoHematologia.bioanalista',
            'analisis.resultadoBacteriologia.bioanalista',
            'analisis.resultadoSerologia.bioanalista',
            'analisis.resultadoColera.bioanalista',
            'analisis.resultadoUroanalisis.bioanalista',
            'analisis.resultadoCoprologia.bioanalista',
            'analisis.resultadoDigestion.bioanalista',
            'analisis.resultadoVarios.bioanalista',
        ]);
        $pdf = Pdf::loadView('pdf.orden_completa', compact('orden'))->setPaper('letter', 'portrait');
        return $pdf->stream('resultados_' . $orden->numero_orden . '.pdf');
    }
}
