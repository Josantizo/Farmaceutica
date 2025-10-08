<?php

namespace App\Http\Controllers;

use App\Models\HistorialStock;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistorialStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $historial = HistorialStock::with(['producto', 'compra', 'venta', 'liquidacion'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('historial-stock.index', compact('historial'));
    }

    /**
     * Display stock history for a specific product
     */
    public function porProducto($productoId): View
    {
        $producto = \App\Models\Producto::findOrFail($productoId);
        
        $historial = HistorialStock::where('producto_id', $productoId)
            ->with(['compra', 'venta', 'liquidacion'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('historial-stock.por-producto', compact('historial', 'producto'));
    }

    /**
     * Display entries by movement type
     */
    public function porTipo($tipo): View
    {
        $historial = HistorialStock::where('tipo_movimiento', $tipo)
            ->with(['producto', 'compra', 'venta', 'liquidacion'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('historial-stock.por-tipo', compact('historial', 'tipo'));
    }
}
