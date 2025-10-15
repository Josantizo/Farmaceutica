<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\HistorialStock;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'compra_id';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'empleado_id',
        'fecha_compra',
        'total'
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'total' => 'decimal:2'
    ];

    // Relaciones
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function detalleCompras(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'compra_id');
    }

    public function historialStock(): HasMany
    {
        return $this->hasMany(HistorialStock::class, 'compra_id');
    }

    /**
     * Intenta obtener la fecha/hora de creación de la compra a partir
     * del primer registro en historial_stock asociado a esta compra.
     * Retorna null si no existe.
     */
    public function createdAtFromHistorial(): ?Carbon
    {
        $h = $this->historialStock()->orderBy('fecha')->first();
        return $h ? Carbon::parse($h->fecha) : null;
    }

    /**
     * Determina si la compra es editable/eliminable dentro de una ventana
     * de minutos (por defecto 60 minutos desde la fecha del historial).
     */
    public function isEditable(int $minutes = 60): bool
    {
        $created = $this->createdAtFromHistorial();
        if (! $created) return true; // si no hay historial, permitir
        return $created->diffInMinutes(Carbon::now()) <= $minutes;
    }

    // Métodos auxiliares
    public function calcularTotal(): float
    {
        return $this->detalleCompras()->sum(\DB::raw('cantidad * precio_unitario'));
    }
}
