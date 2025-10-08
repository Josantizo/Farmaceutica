<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $empleados = Empleado::orderBy('nombre')
            ->paginate(15);

        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'usuario' => 'required|string|max:45|unique:empleados,usuario',
            'contraseña' => 'required|string|max:45',
            'rol' => 'required|string|max:45'
        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado): View
    {
        $empleado->load(['compras', 'ventas']);

        return view('empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado): View
    {
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'usuario' => 'required|string|max:45|unique:empleados,usuario,' . $empleado->empleados_id,
            'contraseña' => 'required|string|max:45',
            'rol' => 'required|string|max:45'
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado): RedirectResponse
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}
