<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Liquidacion extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones';
    protected $primaryKey = 'liquidacion_id';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'empleado_id',
        'fecha_liquidacion',
        'estado',
        'total',
        'observaciones',
    ];

    protected $casts = [
        'fecha_liquidacion' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleLiquidacion::class, 'liquidacion_id');
    }
}


