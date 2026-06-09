<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->orderByDesc('created_at');

        if ($log = $request->get('log')) {
            $query->where('log_name', $log);
        }

        if ($usuario = $request->get('usuario_id')) {
            $query->where('causer_id', $usuario)->where('causer_type', \App\Models\User::class);
        }

        if ($desde = $request->get('desde')) {
            $query->whereDate('created_at', '>=', $desde);
        }

        if ($hasta = $request->get('hasta')) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        $actividades = $query->paginate(30)->withQueryString();
        $usuarios = \App\Models\User::orderBy('name')->get();
        $logs = Activity::distinct('log_name')->pluck('log_name');

        return view('auditoria.index', compact('actividades', 'usuarios', 'logs'));
    }
}
