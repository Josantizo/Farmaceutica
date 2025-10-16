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

    // Relaciones a través de compras y ventas
    public function empleado()
    {
        if ($this->compra_id) {
            return $this->compra->empleado();
        } elseif ($this->venta_id) {
            return $this->venta->empleado();
        } elseif ($this->liquidacion_id) {
            return $this->liquidacion->empleado();
        }
        return null;
    }

    public function proveedor()
    {
        if ($this->compra_id) {
            return $this->compra->proveedor();
        }
        return null;
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

    public function scopePorProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin = null)
    {
        $query->whereDate('fecha', '>=', $fechaInicio);
        if ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }
        return $query;
    }

    public function scopePorEmpleado($query, $empleadoId)
    {
        return $query->where(function($q) use ($empleadoId) {
            $q->whereHas('compra', function($compra) use ($empleadoId) {
                $compra->where('empleado_id', $empleadoId);
            })->orWhereHas('venta', function($venta) use ($empleadoId) {
                $venta->where('empleado_id', $empleadoId);
            })->orWhereHas('liquidacion', function($liquidacion) use ($empleadoId) {
                $liquidacion->where('empleado_id', $empleadoId);
            });
        });
    }

    public function scopePorProveedor($query, $proveedorId)
    {
        return $query->whereHas('compra', function($compra) use ($proveedorId) {
            $compra->where('proveedor_id', $proveedorId);
        });
    }

    public function scopePorPrecio($query, $precioMin, $precioMax = null)
    {
        return $query->whereHas('producto', function($producto) use ($precioMin, $precioMax) {
            if ($precioMax) {
                $producto->whereBetween('precio_venta', [$precioMin, $precioMax]);
            } else {
                $producto->where('precio_venta', '>=', $precioMin);
            }
        });
    }

    // Métodos auxiliares
    public function getEsEntradaAttribute(): bool
    {
        return $this->cantidad_cambio > 0;
    }

    public function getEsSalidaAttribute(): bool
    {
        return $this->cantidad_cambio < 0;
    }

    public function getTipoMovimientoFormateadoAttribute(): string
    {
        return ucfirst(strtolower($this->tipo_movimiento));
    }

    public function getCantidadFormateadaAttribute(): string
    {
        $signo = $this->cantidad_cambio > 0 ? '+' : '';
        return $signo . $this->cantidad_cambio;
    }
}
