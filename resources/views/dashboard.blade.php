<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Farmacéutico - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-pills me-2"></i>Sistema Farmacéutico
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">
                            <i class="fas fa-box me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('compras.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>Compras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ventas.index') }}">
                            <i class="fas fa-cash-register me-1"></i>Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clientes.index') }}">
                            <i class="fas fa-users me-1"></i>Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proveedores.index') }}">
                            <i class="fas fa-truck me-1"></i>Proveedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('empleados.index') }}">
                            <i class="fas fa-user-tie me-1"></i>Empleados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categorias.index') }}">
                            <i class="fas fa-tags me-1"></i>Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('alertas.index') }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>Alertas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('liquidaciones.index') }}">
                            <i class="fas fa-receipt me-1"></i>Liquidaciones
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @if(session('empleado_id'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ session('empleado_nombre') }}
                                <span class="badge bg-secondary ms-1">{{ session('empleado_rol') }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </h1>
            </div>
        </div>

        <div class="row">
            <!-- Tarjetas de resumen -->
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Productos</h4>
                                <p class="card-text">Gestión de inventario</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                        </div>
                        <a href="{{ route('productos.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Productos
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Compras</h4>
                                <p class="card-text">Registro de compras</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                        <a href="{{ route('compras.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Compras
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Ventas</h4>
                                <p class="card-text">Registro de ventas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-cash-register fa-2x"></i>
                            </div>
                        </div>
                        <a href="{{ route('ventas.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Ventas
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Alertas</h4>
                                <p class="card-text">Notificaciones del sistema</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                        <a href="{{ route('alertas.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>Ver Alertas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Acciones rápidas -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Nuevo Producto
                            </a>
                            <a href="{{ route('compras.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Nueva Compra
                            </a>
                            <a href="{{ route('ventas.create') }}" class="btn btn-info">
                                <i class="fas fa-plus me-2"></i>Nueva Venta
                            </a>
                            <a href="{{ route('clientes.create') }}" class="btn btn-secondary">
                                <i class="fas fa-plus me-2"></i>Nuevo Cliente
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>Reportes Rápidos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('productos.bajo-stock') }}" class="btn btn-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Productos Bajo Stock
                            </a>
                            <a href="{{ route('productos.por-vencer') }}" class="btn btn-danger">
                                <i class="fas fa-calendar-times me-2"></i>Productos por Vencer
                            </a>
                            <a href="{{ route('alertas.pendientes') }}" class="btn btn-warning">
                                <i class="fas fa-bell me-2"></i>Alertas Pendientes
                            </a>
                            <a href="{{ route('historial-stock.index') }}" class="btn btn-secondary">
                                <i class="fas fa-history me-2"></i>Historial de Stock
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
