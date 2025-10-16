<?php

namespace App\Http\Controllers;

use App\Models\HistorialStock;
use App\Models\Producto;
use App\Models\Empleado;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistorialStockController extends Controller
{
    /**
     * Display a listing of the resource with advanced filtering
     */
    public function index(Request $request): View
    {
        $query = HistorialStock::with([
            'producto.proveedor', 
            'compra.empleado', 
            'compra.proveedor',
            'venta.empleado',
            'liquidacion.empleado'
        ]);

        // Aplicar filtros
        if ($request->filled('producto_id')) {
            $query->porProducto($request->producto_id);
        }

        if ($request->filled('tipo_movimiento')) {
            $query->porTipo($request->tipo_movimiento);
        }

        if ($request->filled('empleado_id')) {
            $query->porEmpleado($request->empleado_id);
        }

        if ($request->filled('proveedor_id')) {
            $query->porProveedor($request->proveedor_id);
        }

        if ($request->filled('fecha_inicio')) {
            $query->porFecha($request->fecha_inicio, $request->fecha_fin);
        }

        if ($request->filled('precio_min')) {
            $query->porPrecio($request->precio_min, $request->precio_max);
        }

        if ($request->filled('direccion_movimiento')) {
            if ($request->direccion_movimiento === 'entrada') {
                $query->entradas();
            } elseif ($request->direccion_movimiento === 'salida') {
                $query->salidas();
            }
        }

        $historial = $query->orderBy('fecha', 'desc')->paginate(15)->withQueryString();

        // Datos para los filtros
        $productos = Producto::orderBy('nombre')->get();
        $empleados = Empleado::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();
        $tiposMovimiento = HistorialStock::distinct()->pluck('tipo_movimiento')->sort();

        return view('historial-stock.index', compact(
            'historial', 
            'productos', 
            'empleados', 
            'proveedores', 
            'tiposMovimiento'
        ));
    }

    /**
     * Display stock history for a specific product
     */
    public function porProducto(Request $request, $productoId): View
    {
        $producto = Producto::with('proveedor')->findOrFail($productoId);
        
        $query = HistorialStock::where('producto_id', $productoId)
            ->with([
                'compra.empleado', 
                'compra.proveedor',
                'venta.empleado',
                'liquidacion.empleado'
            ]);

        // Aplicar filtros adicionales
        if ($request->filled('tipo_movimiento')) {
            $query->porTipo($request->tipo_movimiento);
        }

        if ($request->filled('empleado_id')) {
            $query->porEmpleado($request->empleado_id);
        }

        if ($request->filled('fecha_inicio')) {
            $query->porFecha($request->fecha_inicio, $request->fecha_fin);
        }

        if ($request->filled('direccion_movimiento')) {
            if ($request->direccion_movimiento === 'entrada') {
                $query->entradas();
            } elseif ($request->direccion_movimiento === 'salida') {
                $query->salidas();
            }
        }

        $historial = $query->orderBy('fecha', 'desc')->paginate(15)->withQueryString();

        // Datos para los filtros
        $empleados = Empleado::orderBy('nombre')->get();
        $tiposMovimiento = HistorialStock::where('producto_id', $productoId)
            ->distinct()->pluck('tipo_movimiento')->sort();

        return view('historial-stock.por-producto', compact(
            'historial', 
            'producto', 
            'empleados', 
            'tiposMovimiento'
        ));
    }

    /**
     * Display entries by movement type
     */
    public function porTipo(Request $request, $tipo): View
    {
        $query = HistorialStock::where('tipo_movimiento', $tipo)
            ->with([
                'producto.proveedor', 
                'compra.empleado', 
                'compra.proveedor',
                'venta.empleado',
                'liquidacion.empleado'
            ]);

        // Aplicar filtros adicionales
        if ($request->filled('producto_id')) {
            $query->porProducto($request->producto_id);
        }

        if ($request->filled('empleado_id')) {
            $query->porEmpleado($request->empleado_id);
        }

        if ($request->filled('proveedor_id')) {
            $query->porProveedor($request->proveedor_id);
        }

        if ($request->filled('fecha_inicio')) {
            $query->porFecha($request->fecha_inicio, $request->fecha_fin);
        }

        if ($request->filled('precio_min')) {
            $query->porPrecio($request->precio_min, $request->precio_max);
        }

        $historial = $query->orderBy('fecha', 'desc')->paginate(15)->withQueryString();

        // Datos para los filtros
        $productos = Producto::orderBy('nombre')->get();
        $empleados = Empleado::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('historial-stock.por-tipo', compact(
            'historial', 
            'tipo', 
            'productos', 
            'empleados', 
            'proveedores'
        ));
    }

    /**
     * Export filtered results to CSV
     */
    public function exportar(Request $request)
    {
        // Implementar exportación CSV si es necesario
        // Por ahora solo redirigir con los filtros aplicados
        return redirect()->route('historial-stock.index', $request->all());
    }
}
