<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // Métodos auxiliares
    public function calcularTotal(): float
    {
        return $this->detalleCompras()->sum(\DB::raw('cantidad * precio_unitario'));
    }
}
