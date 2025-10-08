<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\HistorialStockController;
use App\Http\Controllers\LiquidacionController;
use App\Http\Controllers\AuthController;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Página principal
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Rutas para Productos
Route::resource('productos', ProductoController::class);
Route::get('productos/bajo-stock', [ProductoController::class, 'bajoStock'])->name('productos.bajo-stock');
Route::get('productos/por-vencer', [ProductoController::class, 'porVencer'])->name('productos.por-vencer');
Route::get('productos/create', [ProductoController::class, 'create'])->name('productos.create');

// Rutas para Compras
Route::resource('compras', CompraController::class);

// Rutas para Ventas
Route::resource('ventas', VentaController::class);

// Rutas para Clientes
Route::resource('clientes', ClienteController::class);

// Rutas para Proveedores
Route::resource('proveedores', ProveedorController::class);
Route::post('proveedores/{proveedor}/politica', [ProveedorController::class, 'upsertPolitica'])
    ->name('proveedores.politica.upsert')
    ->middleware('admin');

// Rutas para Empleados
Route::resource('empleados', EmpleadoController::class);

// Rutas para Categorías
Route::resource('categorias', CategoriaController::class);

// Rutas para Alertas
Route::get('alertas', [AlertaController::class, 'index'])->name('alertas.index');
Route::get('alertas/pendientes', [AlertaController::class, 'pendientes'])->name('alertas.pendientes');
Route::get('alertas/tipo/{tipo}', [AlertaController::class, 'porTipo'])->name('alertas.por-tipo');
Route::patch('alertas/{alerta}/resolver', [AlertaController::class, 'resolver'])->name('alertas.resolver');
Route::patch('alertas/{alerta}/pendiente', [AlertaController::class, 'pendiente'])->name('alertas.pendiente');
Route::delete('alertas/{alerta}', [AlertaController::class, 'destroy'])->name('alertas.destroy');

// Rutas para Historial de Stock
Route::get('historial-stock', [HistorialStockController::class, 'index'])->name('historial-stock.index');
Route::get('historial-stock/producto/{productoId}', [HistorialStockController::class, 'porProducto'])->name('historial-stock.por-producto');
Route::get('historial-stock/tipo/{tipo}', [HistorialStockController::class, 'porTipo'])->name('historial-stock.por-tipo');

// Rutas para Liquidaciones (solo Admin)
Route::resource('liquidaciones', LiquidacionController::class)->middleware('admin');
