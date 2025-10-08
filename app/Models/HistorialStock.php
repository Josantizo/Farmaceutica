<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialStock extends Model
{
    use HasFactory;

    protected $table = 'historial_stock';
    protected $primaryKey = 'historial_id';
    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'fecha',
        'cantidad_cambio',
        'tipo_movimiento',
        'compra_id',
        'venta_id',
        'liquidacion_id'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'cantidad_cambio' => 'integer'
    ];

    // Relaciones
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function liquidacion(): BelongsTo
    {
        return $this->belongsTo(Liquidacion::class, 'liquidacion_id');
    }

    // Scopes
    public function scopeEntradas($query)
    {
        return $query->whereIn('tipo_movimiento', ['Compra', 'compra']);
    }

    public function scopeSalidas($query)
    {
        return $query->whereIn('tipo_movimiento', ['Venta', 'venta', 'Ajuste', 'liquidacion']);
    }
}
