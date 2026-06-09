<?php

namespace App\Http\Controllers;

use App\Models\OrdenAnalisis;
use App\Models\ResultadoHematologia;
use App\Models\ResultadoBacteriologia;
use App\Models\ResultadoSerologia;
use App\Models\ResultadoColera;
use App\Models\ResultadoUroanalisis;
use App\Models\ResultadoCoprologia;
use App\Models\ResultadoDigestion;
use App\Models\ResultadoVarios;
use App\Models\User;
use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    private function bioanalistas()
    {
        return User::role('bioanalista')->where('activo', true)->orderBy('name')->get();
    }

    // ── Hematología ────────────────────────────────────────────────────────────

    public function hematologia(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoHematologia ?? new ResultadoHematologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.hematologia', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarHematologia(Request $request, OrdenAnalisis $oa)
    {
        $data = $request->except(['_token', '_method']);
        $data['orden_analisis_id'] = $oa->id;

        ResultadoHematologia::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);

        return back()->with('success', 'Resultado de Hematología guardado.');
    }

    public function validarHematologia(Request $request, OrdenAnalisis $oa)
    {
        abort_unless(auth()->user()->hasRole(['admin', 'bioanalista']), 403);
        $oa->resultadoHematologia?->update([
            'validado'     => true,
            'validado_por' => auth()->id(),
            'validado_at'  => now(),
        ]);
        activity()->on($oa)->log('Hematología validada');
        return back()->with('success', 'Resultado validado.');
    }

    // ── Bacteriología ──────────────────────────────────────────────────────────

    public function bacteriologia(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoBacteriologia ?? new ResultadoBacteriologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.bacteriologia', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarBacteriologia(Request $request, OrdenAnalisis $oa)
    {
        $data = $request->except(['_token', '_method']);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoBacteriologia::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return back()->with('success', 'Resultado de Bacteriología guardado.');
    }

    // ── Serología ──────────────────────────────────────────────────────────────

    public function serologia(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoSerologia ?? new ResultadoSerologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.serologia', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarSerologia(Request $request, OrdenAnalisis $oa)
    {
        $data = $request->except(['_token', '_method']);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoSerologia::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return back()->with('success', 'Serología guardada.');
    }

    // ── Cólera ─────────────────────────────────────────────────────────────────

    public function colera(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoColera ?? new ResultadoColera();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.colera', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarColera(Request $request, OrdenAnalisis $oa)
    {
        $data = $request->except(['_token', '_method']);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoColera::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return back()->with('success', 'Análisis de Cólera guardado.');
    }

    // ── Uroanálisis ────────────────────────────────────────────────────────────

    public function uroanalisis(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $uro  = $oa->resultadoUroanalisis ?? new ResultadoUroanalisis();
        $cop  = $oa->resultadoCoprologia  ?? new ResultadoCoprologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.uroanalisis', compact('oa', 'uro', 'cop', 'bioanalistas'));
    }

    public function guardarUroanalisis(Request $request, OrdenAnalisis $oa)
    {
        $uro = $request->input('uro', []);
        $cop = $request->input('cop', []);
        $uro['orden_analisis_id'] = $oa->id;
        $cop['orden_analisis_id'] = $oa->id;
        ResultadoUroanalisis::updateOrCreate(['orden_analisis_id' => $oa->id], $uro);
        ResultadoCoprologia::updateOrCreate(['orden_analisis_id' => $oa->id], $cop);
        $oa->update(['estado' => 'listo']);
        return back()->with('success', 'Uroanálisis/Coprológico guardado.');
    }

    // ── Digestión en Heces ─────────────────────────────────────────────────────

    public function digestion(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoDigestion ?? new ResultadoDigestion();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.digestion', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarDigestion(Request $request, OrdenAnalisis $oa)
    {
        $data = $request->except(['_token', '_method']);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoDigestion::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return back()->with('success', 'Digestión en Heces guardada.');
    }

    // ── Análisis Varios ────────────────────────────────────────────────────────

    public function varios(OrdenAnalisis $oa)
    {
        $oa->load('orden.paciente');
        $resultados   = $oa->resultadoVarios;
        $bioanalistas = $this->bioanalistas();
        return view('resultados.varios', compact('oa', 'resultados', 'bioanalistas'));
    }

    public function guardarVarios(Request $request, OrdenAnalisis $oa)
    {
        $request->validate([
            'grupo'    => 'required|string',
            'sub_grupo' => 'required|string',
            'resultado' => 'nullable|string',
        ]);

        $data = $request->except(['_token']);
        $data['orden_analisis_id'] = $oa->id;
        $data['bioanalista_id']    = auth()->id();

        ResultadoVarios::create($data);
        $oa->update(['estado' => 'listo']);
        return back()->with('success', 'Análisis agregado.');
    }

    public function eliminarVarios(ResultadoVarios $resultado)
    {
        $resultado->delete();
        return back()->with('success', 'Análisis eliminado.');
    }
}
