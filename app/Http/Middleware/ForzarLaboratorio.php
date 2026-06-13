<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForzarLaboratorio
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->email !== 'mirym@laboclypsa.com') {
            session(['laboratorio_activo_id' => $user->laboratorio_id]);
        }

        return $next($request);
    }
}
