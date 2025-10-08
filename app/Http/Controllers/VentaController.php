<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\HistorialStock;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $ventas = Venta::with(['cliente', 'empleado', 'detalleVentas'])
            ->orderBy('fecha_venta', 'desc')
            ->paginate(15);

        return view('ventas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $empleados = Empleado::orderBy('nombre')->get();
        $productos = Producto::conStock()->orderBy('nombre')->get();

        return view('ventas.create', compact('clientes', 'empleados', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,cliente_id',
            'empleado_id' => 'required|exists:empleados,empleados_id',
            'fecha_venta' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Verificar stock antes de crear la venta
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['producto_id']);
                if (!$producto->tieneStockSuficiente($productoData['cantidad'])) {
                    // Generar alerta por intento de venta que supera stock o sin stock
                    Alerta::create([
                        'producto_id' => $producto->producto_id,
                        'tipo_alerta' => $producto->stock_actual <= 0 ? 'sin stock' : 'venta excede stock',
                        'fecha_generada' => now()->toDateString(),
                        'estado' => 'pendiente',
                    ]);
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}. Stock disponible: {$producto->stock_actual}");
                }
            }

            // Crear la venta
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'empleado_id' => $request->empleado_id,
                'fecha_venta' => $request->fecha_venta,
                'total' => 0
            ]);

            $total = 0;

            // Crear los detalles de venta
            foreach ($request->productos as $productoData) {
                $detalle = DetalleVenta::create([
                    'venta_id' => $venta->ventas_id,
                    'producto_id' => $productoData['producto_id'],
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario']
                ]);

                $total += $detalle->subtotal;

                // Descontar stock del producto (salida)
                $producto = Producto::lockForUpdate()->find($productoData['producto_id']);
                if ($producto->stock_actual < (int)$productoData['cantidad']) {
                    throw new \Exception("La venta supera el stock disponible del producto: {$producto->nombre}.");
                }
                $producto->update([
                    'stock_actual' => $producto->stock_actual - (int)$productoData['cantidad']
                ]);

                // Registrar historial de stock
                HistorialStock::create([
                    'producto_id' => $producto->producto_id,
                    'fecha' => now(),
                    'cantidad_cambio' => -1 * (int)$productoData['cantidad'],
                    'tipo_movimiento' => 'Venta',
                    'compra_id' => null,
                    'venta_id' => $venta->ventas_id,
                    'liquidacion_id' => null,
                ]);

                // Generar alerta si el stock quedó en cero o bajo
                if ($producto->tieneBajoStock()) {
                    Alerta::create([
                        'producto_id' => $producto->producto_id,
                        'tipo_alerta' => 'bajo stock',
                        'fecha_generada' => now()->toDateString(),
                        'estado' => 'pendiente',
                    ]);
                }
            }

            // Actualizar el total de la venta
            $venta->update(['total' => $total]);

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al crear la venta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta): View
    {
        $venta->load(['cliente', 'empleado', 'detalleVentas.producto']);

        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta): View
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $empleados = Empleado::orderBy('nombre')->get();
        $productos = Producto::conStock()->orderBy('nombre')->get();

        $venta->load('detalleVentas');

        return view('ventas.edit', compact('venta', 'clientes', 'empleados', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta): RedirectResponse
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,cliente_id',
            'empleado_id' => 'required|exists:empleados,empleados_id',
            'fecha_venta' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Verificar stock antes de actualizar la venta
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['producto_id']);
                if (!$producto->tieneStockSuficiente($productoData['cantidad'])) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}. Stock disponible: {$producto->stock_actual}");
                }
            }

            // Eliminar detalles existentes
            $venta->detalleVentas()->delete();

            // Actualizar la venta
            $venta->update([
                'cliente_id' => $request->cliente_id,
                'empleado_id' => $request->empleado_id,
                'fecha_venta' => $request->fecha_venta,
                'total' => 0
            ]);

            $total = 0;

            // Crear los nuevos detalles de venta
            foreach ($request->productos as $productoData) {
                $detalle = DetalleVenta::create([
                    'venta_id' => $venta->ventas_id,
                    'producto_id' => $productoData['producto_id'],
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario']
                ]);

                $total += $detalle->subtotal;
            }

            // Actualizar el total de la venta
            $venta->update(['total' => $total]);

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al actualizar la venta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Eliminar detalles de venta (esto activará los triggers)
            $venta->detalleVentas()->delete();
            
            // Eliminar la venta
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}
