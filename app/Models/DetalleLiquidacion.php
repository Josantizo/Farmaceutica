<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleLiquidacion extends Model
{
    use HasFactory;

    protected $table = 'detalle_liquidacion';
    protected $primaryKey = 'detalle_liquidacion_id';
    public $timestamps = false;

    protected $fillable = [
        'liquidacion_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'motivo',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
    ];

    public function liquidacion(): BelongsTo
    {
        return $this->belongsTo(Liquidacion::class, 'liquidacion_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}


