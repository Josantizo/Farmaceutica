<?php

namespace App\Http\Controllers;

use App\Models\Liquidacion;
use App\Models\DetalleLiquidacion;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LiquidacionController extends Controller
{
    public function index(): View
    {
        $liquidaciones = Liquidacion::with(['proveedor'])
            ->orderByDesc('fecha_liquidacion')
            ->paginate(15);

        return view('liquidaciones.index', compact('liquidaciones'));
    }

    public function create(Request $request): View
    {
        $proveedores = Proveedor::orderBy('nombre')->get();

        $proveedorId = $request->input('proveedor_id');
        $productoId = $request->input('producto_id');
        $sugeridos = collect();
        $proveedorSeleccionado = null;

        if ($proveedorId) {
            $proveedorSeleccionado = Proveedor::with('politica')->findOrFail($proveedorId);
            $dias = optional($proveedorSeleccionado->politica)->dias_anticipacion ?? 30;
            $query = Producto::where('proveedor_id', $proveedorId)
                ->where('stock_actual', '>', 0)
                ->whereDate('fecha_vencimiento', '<=', now()->addDays($dias))
                ->orderBy('fecha_vencimiento');

            if ($productoId) {
                $query->where('producto_id', $productoId);
            }

            $sugeridos = $query->get();
        }

        return view('liquidaciones.create', compact('proveedores', 'sugeridos', 'proveedorSeleccionado'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,proveedor_id',
            'fecha_liquidacion' => 'required|date',
            'observaciones' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,producto_id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'items.*.motivo' => 'nullable|string|max:45',
        ]);

        $liquidacion = Liquidacion::create([
            'proveedor_id' => $validated['proveedor_id'],
            'empleado_id' => session('empleado_id'),
            'fecha_liquidacion' => $validated['fecha_liquidacion'],
            'estado' => 'borrador',
            'total' => 0,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            DetalleLiquidacion::create([
                'liquidacion_id' => $liquidacion->liquidacion_id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'motivo' => $item['motivo'] ?? 'por_vencer',
            ]);
        }

        // Recalcula total (por si triggers no están activos)
        $total = $liquidacion->detalles()->sum(\DB::raw('cantidad * precio_unitario'));
        $liquidacion->update(['total' => $total]);

        return redirect()->route('liquidaciones.show', $liquidacion)
            ->with('success', 'Liquidación creada exitosamente.');
    }

    public function show(Liquidacion $liquidacione): View
    {
        $liquidacione->load(['proveedor', 'detalles.producto']);
        return view('liquidaciones.show', ['liquidacion' => $liquidacione]);
    }

    public function edit(Liquidacion $liquidacione): View
    {
        $liquidacione->load(['proveedor', 'detalles.producto']);
        $productos = Producto::where('proveedor_id', $liquidacione->proveedor_id)
            ->orderBy('nombre')
            ->get();
        return view('liquidaciones.edit', ['liquidacion' => $liquidacione, 'productos' => $productos]);
    }

    public function update(Request $request, Liquidacion $liquidacione): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => 'nullable|in:borrador,enviada,aceptada,rechazada',
            'observaciones' => 'nullable|string|max:255',
            'items' => 'nullable|array',
            'items.*.detalle_liquidacion_id' => 'nullable|exists:detalle_liquidacion,detalle_liquidacion_id',
            'items.*.producto_id' => 'required_with:items|exists:productos,producto_id',
            'items.*.cantidad' => 'required_with:items|integer|min:1',
            'items.*.precio_unitario' => 'required_with:items|numeric|min:0',
            'items.*.motivo' => 'nullable|string|max:45',
        ]);

        if (isset($validated['estado'])) {
            $liquidacione->estado = $validated['estado'];
        }
        if (array_key_exists('observaciones', $validated)) {
            $liquidacione->observaciones = $validated['observaciones'];
        }
        $liquidacione->save();

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                if (!empty($item['detalle_liquidacion_id'])) {
                    $detalle = DetalleLiquidacion::findOrFail($item['detalle_liquidacion_id']);
                    $detalle->update([
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'motivo' => $item['motivo'] ?? 'por_vencer',
                    ]);
                } else {
                    DetalleLiquidacion::create([
                        'liquidacion_id' => $liquidacione->liquidacion_id,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'motivo' => $item['motivo'] ?? 'por_vencer',
                    ]);
                }
            }
        }

        $total = $liquidacione->detalles()->sum(\DB::raw('cantidad * precio_unitario'));
        $liquidacione->update(['total' => $total]);

        return redirect()->route('liquidaciones.show', $liquidacione)
            ->with('success', 'Liquidación actualizada.');
    }

    public function destroy(Liquidacion $liquidacione): RedirectResponse
    {
        if ($liquidacione->estado !== 'borrador') {
            return back()->with('error', 'Solo se pueden eliminar liquidaciones en borrador.');
        }

        $liquidacione->delete();
        return redirect()->route('liquidaciones.index')->with('success', 'Liquidación eliminada.');
    }
}


