<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'producto_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock_actual',
        'fecha_vencimiento',
        'lote',
        'categoria_id',
        'proveedor_id'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock_actual' => 'integer'
    ];

    // Relaciones
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function detalleCompras(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'producto_id');
    }

    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id');
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class, 'producto_id');
    }

    public function historialStock(): HasMany
    {
        return $this->hasMany(HistorialStock::class, 'producto_id');
    }

    // Scopes
    public function scopeConStock($query)
    {
        return $query->where('stock_actual', '>', 0);
    }

    public function scopePorVencer($query, $dias = 30)
    {
        return $query->where('fecha_vencimiento', '<=', now()->addDays($dias));
    }

    public function scopeBajoStock($query, $minimo = 10)
    {
        return $query->where('stock_actual', '<', $minimo);
    }

    // Métodos auxiliares
    public function tieneStockSuficiente($cantidad): bool
    {
        return $this->stock_actual >= $cantidad;
    }

    public function estaPorVencer($dias = 30): bool
    {
        return $this->fecha_vencimiento <= now()->addDays($dias);
    }

    public function tieneBajoStock($minimo = 10): bool
    {
        return $this->stock_actual < $minimo;
    }
}
