<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Paciente;
use App\Models\OrdenAnalisis;
use App\Models\AnalisisTipo;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::with(['laboratorio',
            'ordenes' => fn ($q) => $q->latest()->limit(1)
        ])->orderByDesc('id');

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'ilike', "%$buscar%")
                  ->orWhere('cedula', 'ilike', "%$buscar%")
                  ->orWhere('codigo', 'ilike', "%$buscar%")
                  ->orWhereHas('ordenes', fn ($o) => $o->where('numero_orden', 'ilike', "%$buscar%"));
            });
        }

        $pacientes = $query->paginate(50)->withQueryString();
        return view('ordenes.index', compact('pacientes'));
    }

    public function create(Request $request)
    {
        $paciente = null;
        if ($request->has('paciente_id')) {
            $paciente = Paciente::findOrFail($request->paciente_id);
        }

        $categorias = AnalisisTipo::where('activo', true)
            ->orderBy('categoria')->orderBy('nombre')
            ->get()->groupBy('categoria');

        return view('ordenes.create', compact('paciente', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id'     => 'required|exists:pacientes,id',
            'tipo_paciente'   => 'required|in:ambulatorio,internado',
            'fecha_entrada'   => 'required|date',
            'numero_factura'  => 'nullable|string|max:50',
            'laboratorio_id'  => 'required|exists:laboratorios,id',
            'analisis_ids'    => 'required|array|min:1',
            'analisis_ids.*'  => 'exists:analisis_tipos,id',
        ]);

        $orden = Orden::create([
            'numero_orden'   => Orden::generarNumero(),
            'numero_entrada' => Orden::withTrashed()->count() + 1,
            'paciente_id'    => $request->paciente_id,
            'tipo_paciente'  => $request->tipo_paciente,
            'fecha_entrada'  => $request->fecha_entrada,
            'numero_factura' => $request->numero_factura,
            'embarazada'     => $request->boolean('embarazada'),
            'laboratorio_id' => $request->laboratorio_id,
            'creado_por'     => auth()->id(),
            'estado'         => 'pendiente',
        ]);

        // Batch insert: 1 query en lugar de N queries
        $now = now();
        OrdenAnalisis::insert(
            collect($request->analisis_ids)->map(fn ($id) => [
                'orden_id'         => $orden->id,
                'analisis_tipo_id' => (int) $id,
                'estado'           => 'pendiente',
                'created_at'       => $now,
                'updated_at'       => $now,
            ])->toArray()
        );

        activity()->on($orden)->log('Orden creada con ' . count($request->analisis_ids) . ' análisis');

        return redirect()->route('ordenes.show', $orden)->with('success', 'Orden #' . $orden->numero_orden . ' creada correctamente.');
    }

    public function show(Orden $orden)
    {
        $orden->load(['paciente', 'laboratorio', 'creadoPor', 'validadoPor',
            'analisis.tipo',
            'analisis.resultadoHematologia',
            'analisis.resultadoBacteriologia',
            'analisis.resultadoSerologia',
            'analisis.resultadoColera',
            'analisis.resultadoUroanalisis',
            'analisis.resultadoCoprologia',
            'analisis.resultadoDigestion',
            'analisis.resultadoVarios',
        ]);

        $tiposExistentes = $orden->analisis->pluck('analisis_tipo_id')->toArray();
        $categorias = AnalisisTipo::where('activo', true)
            ->whereNotIn('id', $tiposExistentes)
            ->orderBy('categoria')->orderBy('nombre')
            ->get()->groupBy('categoria');

        return view('ordenes.show', compact('orden', 'categorias'));
    }

    public function agregarAnalisis(Request $request, Orden $orden)
    {
        $request->validate([
            'analisis_ids'   => 'required|array|min:1',
            'analisis_ids.*' => 'exists:analisis_tipos,id',
        ]);

        $tiposExistentes = $orden->analisis()->pluck('analisis_tipo_id')->toArray();
        $nuevos = collect($request->analisis_ids)
            ->filter(fn ($id) => !in_array((int) $id, $tiposExistentes))
            ->values();

        if ($nuevos->isEmpty()) {
            return back()->with('error', 'Los análisis seleccionados ya están en esta orden.');
        }

        $now = now();
        OrdenAnalisis::insert(
            $nuevos->map(fn ($id) => [
                'orden_id'         => $orden->id,
                'analisis_tipo_id' => (int) $id,
                'estado'           => 'pendiente',
                'created_at'       => $now,
                'updated_at'       => $now,
            ])->toArray()
        );

        activity()->on($orden)->log('Agregados ' . $nuevos->count() . ' análisis a la orden');

        return redirect()->route('ordenes.show', $orden)
            ->with('success', $nuevos->count() . ' análisis agregado(s) a la orden.');
    }

    public function validar(Orden $orden)
    {
        abort_unless(auth()->user()->hasRole(['admin', 'bioanalista']), 403);
        $orden->update([
            'estado'       => 'validado',
            'validado_por' => auth()->id(),
            'validado_at'  => now(),
        ]);
        activity()->on($orden)->log('Orden validada');
        return back()->with('success', 'Orden validada correctamente.');
    }
}
