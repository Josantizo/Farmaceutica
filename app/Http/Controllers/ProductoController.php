<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $productos = Producto::with(['categoria', 'proveedor'])
            ->orderBy('nombre')
            ->paginate(15);

        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('productos.create', compact('categorias', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'descripcion' => 'required|string|max:45',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'fecha_vencimiento' => 'required|date',
            'lote' => 'required|string|max:45',
            'categoria_id' => 'required|exists:categorias,categoria_id',
            'proveedor_id' => 'required|exists:proveedores,proveedor_id'
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto): View
    {
        $producto->load(['categoria', 'proveedor', 'alertas', 'historialStock']);

        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto): View
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:45',
            'descripcion' => 'required|string|max:45',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'fecha_vencimiento' => 'required|date',
            'lote' => 'required|string|max:45',
            'categoria_id' => 'required|exists:categorias,categoria_id',
            'proveedor_id' => 'required|exists:proveedores,proveedor_id'
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Show products with low stock
     */
    public function bajoStock(): View
    {
        $productos = Producto::bajoStock()
            ->with(['categoria', 'proveedor'])
            ->orderBy('stock_actual')
            ->paginate(15);

        return view('productos.bajo-stock', compact('productos'));
    }

    /**
     * Show products about to expire
     */
    public function porVencer(): View
    {
        $productos = Producto::porVencer()
            ->with(['categoria', 'proveedor'])
            ->orderBy('fecha_vencimiento')
            ->paginate(15);

        return view('productos.por-vencer', compact('productos'));
    }
}
