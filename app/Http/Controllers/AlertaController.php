<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AlertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $alertas = Alerta::with('producto')
            ->orderBy('fecha_generada', 'desc')
            ->paginate(15);

        return view('alertas.index', compact('alertas'));
    }

    /**
     * Display pending alerts
     */
    public function pendientes(): View
    {
        $alertas = Alerta::pendientes()
            ->with('producto')
            ->orderBy('fecha_generada', 'desc')
            ->paginate(15);

        return view('alertas.pendientes', compact('alertas'));
    }

    /**
     * Display alerts by type
     */
    public function porTipo($tipo): View
    {
        $alertas = Alerta::where('tipo_alerta', $tipo)
            ->with('producto')
            ->orderBy('fecha_generada', 'desc')
            ->paginate(15);

        return view('alertas.por-tipo', compact('alertas', 'tipo'));
    }

    /**
     * Mark alert as resolved
     */
    public function resolver(Alerta $alerta): RedirectResponse
    {
        $alerta->update(['estado' => 'resuelto']);

        return redirect()->back()
            ->with('success', 'Alerta marcada como resuelta.');
    }

    /**
     * Mark alert as pending
     */
    public function pendiente(Alerta $alerta): RedirectResponse
    {
        $alerta->update(['estado' => 'pendiente']);

        return redirect()->back()
            ->with('success', 'Alerta marcada como pendiente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alerta $alerta): RedirectResponse
    {
        $alerta->delete();

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta eliminada exitosamente.');
    }

    /**
     * Crear una alerta por vencimiento para un producto y redirigir
     * al formulario de creación de liquidación con proveedor/producto preseleccionados.
     */
    public function crearPorVencer(Request $request): RedirectResponse
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,producto_id'
        ]);

        $producto = \App\Models\Producto::with('proveedor')->findOrFail($request->producto_id);

        $alerta = Alerta::create([
            'producto_id' => $producto->producto_id,
            'tipo_alerta' => 'por_vencer',
            'fecha_generada' => now()->toDateString(),
            'estado' => 'pendiente',
        ]);

        // Redirigir al formulario de creación de liquidación con proveedor_id y producto_id
        return redirect()->route('liquidaciones.create', [
            'proveedor_id' => $producto->proveedor_id,
            'producto_id' => $producto->producto_id,
        ])->with('success', 'Alerta creada y se abrirá el formulario de liquidación.');
    }
}
