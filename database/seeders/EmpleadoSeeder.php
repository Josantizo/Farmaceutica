<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        $empleados = [
            [
                'nombre' => 'Admin Sistema',
                'usuario' => 'admin',
                'contraseña' => 'admin123',
                'rol' => 'Admin',
            ],
            [
                'nombre' => 'Juan Operador',
                'usuario' => 'operador',
                'contraseña' => 'operador123',
                'rol' => 'Operador',
            ],
            [
                'nombre' => 'María Vendedora',
                'usuario' => 'maria',
                'contraseña' => 'maria123',
                'rol' => 'Operador',
            ],
        ];

        foreach ($empleados as $empleado) {
            Empleado::create($empleado);
        }
    }
}
