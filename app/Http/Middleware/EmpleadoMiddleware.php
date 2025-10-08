<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpleadoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('empleado_id')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }

        return $next($request);
    }
}
