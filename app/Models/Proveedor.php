<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'proveedor_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'correo'
    ];

    // Relaciones
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'proveedor_id');
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'proveedor_id');
    }

    public function politica(): HasOne
    {
        return $this->hasOne(ProveedorPolitica::class, 'proveedor_id');
    }

    public function liquidaciones(): HasMany
    {
        return $this->hasMany(Liquidacion::class, 'proveedor_id');
    }
}
