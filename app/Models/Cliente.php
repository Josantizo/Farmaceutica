<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'cliente_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'correo'
    ];

    // Relaciones
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    // Métodos auxiliares
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre;
    }
}
