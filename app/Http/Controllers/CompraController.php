<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Empleado;
use App\Models\HistorialStock;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $compras = Compra::with(['proveedor', 'empleado', 'detalleCompras'])
            ->orderBy('fecha_compra', 'desc')
            ->paginate(15);

        return view('compras.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $empleados = Empleado::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();

        return view('compras.create', compact('proveedores', 'empleados', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,proveedor_id',
            'empleado_id' => 'required|exists:empleados,empleados_id',
            'fecha_compra' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Crear la compra
            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'empleado_id' => $request->empleado_id,
                'fecha_compra' => $request->fecha_compra,
                'total' => 0
            ]);

            $total = 0;

            // Crear los detalles de compra
            foreach ($request->productos as $productoData) {
                $detalle = DetalleCompra::create([
                    'compra_id' => $compra->compra_id,
                    'producto_id' => $productoData['producto_id'],
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario']
                ]);

                $total += $detalle->subtotal;

                // Actualizar stock del producto (entrada)
                $producto = Producto::lockForUpdate()->find($productoData['producto_id']);
                $producto->update([
                    'stock_actual' => $producto->stock_actual + (int)$productoData['cantidad']
                ]);

                // Registrar historial de stock
                HistorialStock::create([
                    'producto_id' => $producto->producto_id,
                    'fecha' => now(),
                    'cantidad_cambio' => (int)$productoData['cantidad'],
                    'tipo_movimiento' => 'Compra',
                    'compra_id' => $compra->compra_id,
                    'venta_id' => null,
                    'liquidacion_id' => null,
                ]);
            }

            // Actualizar el total de la compra
            $compra->update(['total' => $total]);

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al crear la compra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra): View
    {
        $compra->load(['proveedor', 'empleado', 'detalleCompras.producto']);

        return view('compras.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra): View
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $empleados = Empleado::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();

        $compra->load('detalleCompras');

        return view('compras.edit', compact('compra', 'proveedores', 'empleados', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra): RedirectResponse
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,proveedor_id',
            'empleado_id' => 'required|exists:empleados,empleados_id',
            'fecha_compra' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Eliminar detalles existentes
            $compra->detalleCompras()->delete();

            // Actualizar la compra
            $compra->update([
                'proveedor_id' => $request->proveedor_id,
                'empleado_id' => $request->empleado_id,
                'fecha_compra' => $request->fecha_compra,
                'total' => 0
            ]);

            $total = 0;

            // Crear los nuevos detalles de compra
            foreach ($request->productos as $productoData) {
                $detalle = DetalleCompra::create([
                    'compra_id' => $compra->compra_id,
                    'producto_id' => $productoData['producto_id'],
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario']
                ]);

                $total += $detalle->subtotal;
            }

            // Actualizar el total de la compra
            $compra->update(['total' => $total]);

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Eliminar detalles de compra (esto activará los triggers)
            $compra->detalleCompras()->delete();
            
            // Eliminar la compra
            $compra->delete();

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}
