<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compra';
    protected $primaryKey = 'detallecompra_id';
    public $timestamps = false;

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio_unitario'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2'
    ];

    // Relaciones
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Métodos auxiliares
    public function getSubtotalAttribute(): float
    {
        return $this->cantidad * $this->precio_unitario;
    }
}
