<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'empleados_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'usuario',
        'contraseña',
        'rol'
    ];

    // Relaciones
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'empleado_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'empleado_id');
    }

    public function liquidaciones(): HasMany
    {
        return $this->hasMany(Liquidacion::class, 'empleado_id');
    }

    // Métodos auxiliares
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre;
    }
}
