<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\ProveedorPolitica;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $proveedores = Proveedor::orderBy('nombre')
            ->paginate(15);

        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'nit' => 'required|string|max:45',
            'direccion' => 'required|string|max:45',
            'telefono' => 'required|string|max:45',
            'correo' => 'required|email|max:45'
        ]);

        $proveedor = Proveedor::create($request->all());

        if ($request->wantsJson()) {
            return response()->json(['proveedor' => $proveedor], 201);
        }

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor): View
    {
        $proveedor->load(['productos', 'compras.detalleCompras.producto', 'politica']);

        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Create or update supplier policy
     */
    public function upsertPolitica(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $validated = $request->validate([
            'dias_anticipacion' => 'required|integer|min:0|max:3650',
            'metodo_liquidacion' => 'nullable|string|max:45',
            'porcentaje_credito' => 'nullable|numeric|min:0|max:100',
            'producto_id' => 'nullable|exists:productos,producto_id',
        ]);

        $payload = [
            'dias_anticipacion' => $validated['dias_anticipacion'],
            'metodo_liquidacion' => $validated['metodo_liquidacion'] ?? null,
            'porcentaje_credito' => $validated['porcentaje_credito'] ?? null,
            'producto_id' => $validated['producto_id'] ?? null,
            'fecha_actualizacion' => now(),
        ];

        ProveedorPolitica::updateOrCreate(
            ['proveedor_id' => $proveedor->proveedor_id],
            array_merge(['proveedor_id' => $proveedor->proveedor_id], $payload)
        );

        return redirect()->route('proveedores.show', $proveedor)
            ->with('success', 'Política actualizada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor): View
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'nit' => 'required|string|max:45',
            'direccion' => 'required|string|max:45',
            'telefono' => 'required|string|max:45',
            'correo' => 'required|email|max:45'
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
