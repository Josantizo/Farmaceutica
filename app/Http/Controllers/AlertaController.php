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
}
