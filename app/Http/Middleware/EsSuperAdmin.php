<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(
            auth()->check() && auth()->user()->email === 'mirym@laboclypsa.com',
            403,
            'No tienes permiso para acceder a esta sección.'
        );
        return $next($request);
    }
}
