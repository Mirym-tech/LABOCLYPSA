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
        return cache()->remember('bioanalistas_activos', 300, fn () =>
            User::role('bioanalista')
                ->where('activo', true)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
        );
    }

    private function verificarAcceso(OrdenAnalisis $oa): void
    {
        $user = auth()->user();

        // Admin siempre pasa (doble chequeo: rol + email por si hay caché stale)
        if ($user->hasRole('admin') || $user->email === 'mirym@laboclypsa.com') return;

        $oa->loadMissing('orden.laboratorio');

        // Pasa si la orden es del laboratorio asignado al usuario (comparación no estricta para evitar int/string mismatch)
        if ($user->laboratorio_id !== null && (int) $oa->orden->laboratorio_id === (int) $user->laboratorio_id) return;

        // Pasa si el usuario mismo creó la orden
        if ((int) $oa->orden->creado_por === (int) $user->id) return;

        $labOrden   = $oa->orden->laboratorio?->nombre ?? "ID {$oa->orden->laboratorio_id}";
        $labUsuario = $user->laboratorio?->nombre       ?? ($user->laboratorio_id ? "ID {$user->laboratorio_id}" : 'sin laboratorio asignado');

        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            redirect()->route('ordenes.show', $oa->orden_id)
                ->with('error', "No tienes acceso: la orden pertenece a «{$labOrden}» y tú estás asignado/a a «{$labUsuario}». Pide al administrador que corrija tu laboratorio.")
        );
    }

    private function datosResultado(Request $request): array
    {
        return $request->except(['_token', '_method', 'validado', 'validado_por', 'validado_at']);
    }

    // ── Hematología ────────────────────────────────────────────────────────────

    public function hematologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoHematologia ?? new ResultadoHematologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.hematologia', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarHematologia(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $data = $this->datosResultado($request);
        $data['orden_analisis_id'] = $oa->id;

        ResultadoHematologia::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);

        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Resultado de Hematología guardado.');
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
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Resultado validado.');
    }

    // ── Bacteriología ──────────────────────────────────────────────────────────

    public function bacteriologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoBacteriologia ?? new ResultadoBacteriologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.bacteriologia', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarBacteriologia(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $data = $this->datosResultado($request);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoBacteriologia::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Resultado de Bacteriología guardado.');
    }

    // ── Serología ──────────────────────────────────────────────────────────────

    public function serologia(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoSerologia ?? new ResultadoSerologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.serologia', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarSerologia(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $data = $this->datosResultado($request);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoSerologia::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Serología guardada.');
    }

    // ── Cólera ─────────────────────────────────────────────────────────────────

    public function colera(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoColera ?? new ResultadoColera();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.colera', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarColera(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $data = $this->datosResultado($request);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoColera::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Análisis de Cólera guardado.');
    }

    // ── Uroanálisis ────────────────────────────────────────────────────────────

    public function uroanalisis(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $uro  = $oa->resultadoUroanalisis ?? new ResultadoUroanalisis();
        $cop  = $oa->resultadoCoprologia  ?? new ResultadoCoprologia();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.uroanalisis', compact('oa', 'uro', 'cop', 'bioanalistas'));
    }

    public function guardarUroanalisis(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $camposBloqueados = ['validado', 'validado_por', 'validado_at', 'orden_analisis_id'];
        $uro = collect($request->input('uro', []))->except($camposBloqueados)->toArray();
        $cop = collect($request->input('cop', []))->except($camposBloqueados)->toArray();
        $uro['orden_analisis_id'] = $oa->id;
        $cop['orden_analisis_id'] = $oa->id;
        ResultadoUroanalisis::updateOrCreate(['orden_analisis_id' => $oa->id], $uro);
        ResultadoCoprologia::updateOrCreate(['orden_analisis_id' => $oa->id], $cop);
        $oa->update(['estado' => 'listo']);
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Uroanálisis/Coprológico guardado.');
    }

    // ── Digestión en Heces ─────────────────────────────────────────────────────

    public function digestion(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $resultado = $oa->resultadoDigestion ?? new ResultadoDigestion();
        $bioanalistas = $this->bioanalistas();
        return view('resultados.digestion', compact('oa', 'resultado', 'bioanalistas'));
    }

    public function guardarDigestion(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $data = $this->datosResultado($request);
        $data['orden_analisis_id'] = $oa->id;
        ResultadoDigestion::updateOrCreate(['orden_analisis_id' => $oa->id], $data);
        $oa->update(['estado' => 'listo']);
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Digestión en Heces guardada.');
    }

    // ── Análisis Varios ────────────────────────────────────────────────────────

    public function varios(OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $oa->load('orden.paciente');
        $resultados   = $oa->resultadoVarios;
        $bioanalistas = $this->bioanalistas();
        return view('resultados.varios', compact('oa', 'resultados', 'bioanalistas'));
    }

    public function guardarVarios(Request $request, OrdenAnalisis $oa)
    {
        $this->verificarAcceso($oa);
        $validated = $request->validate([
            'grupo'     => 'required|string|max:100',
            'sub_grupo' => 'required|string|max:200',
            'resultado' => 'nullable|string|max:500',
            'valor_ref' => 'nullable|string|max:200',
            'medidas'   => 'nullable|string|max:100',
            'metodo'    => 'nullable|string|max:200',
            'muestra'   => 'nullable|string|max:100',
        ]);

        $validated['orden_analisis_id'] = $oa->id;
        $validated['bioanalista_id']    = auth()->id();

        ResultadoVarios::create($validated);
        $oa->update(['estado' => 'listo']);
        return redirect()->route('ordenes.show', $oa->orden_id)->with('success', 'Análisis agregado.');
    }

    public function eliminarVarios(ResultadoVarios $resultado)
    {
        $this->verificarAcceso($resultado->ordenAnalisis);
        abort_unless(auth()->user()->hasRole(['admin', 'bioanalista']), 403);
        $resultado->delete();
        return back()->with('success', 'Análisis eliminado.');
    }
}
