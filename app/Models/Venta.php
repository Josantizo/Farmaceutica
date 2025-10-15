<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\HistorialStock;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $primaryKey = 'ventas_id';
    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'fecha_venta',
        'total'
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'total' => 'decimal:2'
    ];

    // Relaciones
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function historialStock(): HasMany
    {
        return $this->hasMany(HistorialStock::class, 'venta_id');
    }

    public function createdAtFromHistorial(): ?Carbon
    {
        $h = $this->historialStock()->orderBy('fecha')->first();
        return $h ? Carbon::parse($h->fecha) : null;
    }

    public function isEditable(int $minutes = 60): bool
    {
        $created = $this->createdAtFromHistorial();
        if (! $created) return true;
        return $created->diffInMinutes(Carbon::now()) <= $minutes;
    }

    // Métodos auxiliares
    public function calcularTotal(): float
    {
        return $this->detalleVentas()->sum(\DB::raw('cantidad * precio_unitario'));
    }
}
