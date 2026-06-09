<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::with('laboratorio');

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'ilike', "%$buscar%")
                  ->orWhere('cedula', 'ilike', "%$buscar%")
                  ->orWhere('codigo', 'ilike', "%$buscar%");
            });
        }

        $pacientes = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        $laboratorios = Laboratorio::where('activo', true)->get();
        return view('pacientes.create', compact('laboratorios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:200',
            'cedula'          => 'nullable|string|max:20|unique:pacientes',
            'direccion'       => 'nullable|string|max:255',
            'telefono'        => 'nullable|string|max:20',
            'edad'            => 'nullable|integer|min:0|max:150',
            'sexo'            => 'nullable|in:F,M',
            'nacionalidad'    => 'required|in:dominicana,haitiana,otra',
            'medico_tratante' => 'nullable|string|max:200',
            'seguro_medico'   => 'nullable|string|max:100',
            'cuenta'          => 'nullable|string|max:50',
            'laboratorio_id'  => 'required|exists:laboratorios,id',
        ]);

        $data['codigo']      = strtoupper(Str::random(2)) . now()->format('ymdHi');
        $data['creado_por']  = auth()->id();

        $paciente = Paciente::create($data);
        activity()->on($paciente)->log('Paciente creado');

        return redirect()->route('pacientes.show', $paciente)->with('success', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['ordenes.analisis.tipo', 'laboratorio']);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        $laboratorios = Laboratorio::where('activo', true)->get();
        return view('pacientes.edit', compact('paciente', 'laboratorios'));
    }

    public function destroy($id)
    {
        $paciente = Paciente::withTrashed()->findOrFail($id);
        // Soft-delete todas las órdenes asociadas antes de borrar el paciente
        $paciente->ordenes()->delete();
        $paciente->delete();
        return redirect()->route('ordenes.index')->with('success', 'Paciente eliminado correctamente.');
    }

    public function update(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:200',
            'cedula'          => 'nullable|string|max:20|unique:pacientes,cedula,' . $paciente->id,
            'direccion'       => 'nullable|string|max:255',
            'telefono'        => 'nullable|string|max:20',
            'edad'            => 'nullable|integer|min:0|max:150',
            'sexo'            => 'nullable|in:F,M',
            'nacionalidad'    => 'required|in:dominicana,haitiana,otra',
            'medico_tratante' => 'nullable|string|max:200',
            'seguro_medico'   => 'nullable|string|max:100',
            'cuenta'          => 'nullable|string|max:50',
        ]);

        $paciente->update($data);
        return redirect()->route('pacientes.show', $paciente)->with('success', 'Paciente actualizado.');
    }
}
