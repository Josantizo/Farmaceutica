<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';
    protected $primaryKey = 'alerta_id';
    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'tipo_alerta',
        'fecha_generada',
        'estado'
    ];

    protected $casts = [
        'fecha_generada' => 'date'
    ];

    // Relaciones
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeResueltas($query)
    {
        return $query->where('estado', 'resuelto');
    }

    public function scopeVencimiento($query)
    {
        return $query->where('tipo_alerta', 'vencimiento');
    }

    public function scopeBajoStock($query)
    {
        return $query->where('tipo_alerta', 'bajo stock');
    }
}
