<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProveedorPolitica extends Model
{
    use HasFactory;

    protected $table = 'proveedor_politicas';
    protected $primaryKey = 'proveedor_politica_id';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'dias_anticipacion',
        'metodo_liquidacion',
        'porcentaje_credito',
        'producto_id',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'dias_anticipacion' => 'integer',
        'porcentaje_credito' => 'decimal:2',
        'fecha_actualizacion' => 'datetime',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}


