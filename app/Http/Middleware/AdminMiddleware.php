<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('empleado_id')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }

        if (session('empleado_rol') !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado. Solo administradores.');
        }

        return $next($request);
    }
}
