<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'usuario' => 'required|string',
            'contraseña' => 'required|string',
        ]);

        $empleado = Empleado::where('usuario', $request->usuario)->first();

        if ($empleado && $empleado->contraseña === $request->contraseña) {
            Session::put('empleado_id', $empleado->empleados_id);
            Session::put('empleado_nombre', $empleado->nombre);
            Session::put('empleado_rol', $empleado->rol);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Bienvenido, ' . $empleado->nombre);
        }

        return back()->withErrors([
            'usuario' => 'Credenciales incorrectas.',
        ])->withInput($request->only('usuario'));
    }

    public function logout(): RedirectResponse
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
