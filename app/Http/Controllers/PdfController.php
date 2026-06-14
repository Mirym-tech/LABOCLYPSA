<?php

namespace App\Http\Controllers;

use App\Models\OrdenAnalisis;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    private function verificarAcceso(OrdenAnalisis $oa): void
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->email === 'mirym@laboclypsa.com') return;

        $oa->loadMissing('orden');

        if ($user->laboratorio_id !== null && (int) $oa->orden->laboratorio_id === (int) $user->laboratorio_id) return;

        if ((int) $oa->orden->creado_por === (int) $user->id) return;

        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            redirect()->route('ordenes.show', $oa->orden_id)
                ->with('error', 'No tienes acceso a resultados de este laboratorio.')
        );
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
}
