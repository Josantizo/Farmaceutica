@php($title = 'Historial de Stock - ' . $producto->nombre)

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .filter-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .badge-entrada {
            background-color: #28a745;
        }
        .badge-salida {
            background-color: #dc3545;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .product-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0 text-dark">
                        <i class="bi bi-clock-history me-2"></i>Historial de Stock
                    </h1>
                    <a href="{{ route('historial-stock.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Volver al Historial
                    </a>
                </div>

                <!-- Información del Producto -->
                <div class="product-info">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="h4 mb-2">
                                <i class="bi bi-box me-2"></i>{{ $producto->nombre }}
                            </h2>
                            @if($producto->descripcion)
                                <p class="mb-2 opacity-75">{{ $producto->descripcion }}</p>
                            @endif
                            <div class="row">
                                <div class="col-sm-6">
                                    <small class="opacity-75">
                                        <i class="bi bi-box-seam me-1"></i>
                                        Stock actual: <strong>{{ $producto->stock_actual }}</strong>
                                    </small>
                                </div>
                                <div class="col-sm-6">
                                    @if($producto->proveedor)
                                        <small class="opacity-75">
                                            <i class="bi bi-building me-1"></i>
                                            Proveedor: <strong>{{ $producto->proveedor->nombre }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <small class="opacity-75">Precio de Compra</small>
                                <div class="h5 mb-0">${{ number_format($producto->precio_compra, 2) }}</div>
                            </div>
                            <div>
                                <small class="opacity-75">Precio de Venta</small>
                                <div class="h5 mb-0">${{ number_format($producto->precio_venta, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Filtros -->
                <div class="filter-card">
                    <form method="GET" action="{{ route('historial-stock.por-producto', $producto->producto_id) }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                                <select class="form-select" id="tipo_movimiento" name="tipo_movimiento">
                                    <option value="">Todos los tipos</option>
                                    @foreach($tiposMovimiento as $tipo)
                                        <option value="{{ $tipo }}" 
                                            {{ request('tipo_movimiento') == $tipo ? 'selected' : '' }}>
                                            {{ ucfirst(strtolower($tipo)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="empleado_id" class="form-label">Empleado</label>
                                <select class="form-select" id="empleado_id" name="empleado_id">
                                    <option value="">Todos los empleados</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->empleados_id }}" 
                                            {{ request('empleado_id') == $empleado->empleados_id ? 'selected' : '' }}>
                                            {{ $empleado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="direccion_movimiento" class="form-label">Dirección del Movimiento</label>
                                <select class="form-select" id="direccion_movimiento" name="direccion_movimiento">
                                    <option value="">Todos</option>
                                    <option value="entrada" {{ request('direccion_movimiento') == 'entrada' ? 'selected' : '' }}>
                                        <i class="bi bi-arrow-up"></i> Entradas
                                    </option>
                                    <option value="salida" {{ request('direccion_movimiento') == 'salida' ? 'selected' : '' }}>
                                        <i class="bi bi-arrow-down"></i> Salidas
                                    </option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                       value="{{ request('fecha_inicio') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                       value="{{ request('fecha_fin') }}">
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search me-1"></i>Filtrar
                                </button>
                                <a href="{{ route('historial-stock.por-producto', $producto->producto_id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabla de Resultados -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                                <th><i class="bi bi-tag me-1"></i>Tipo</th>
                                <th class="text-end"><i class="bi bi-arrow-up-down me-1"></i>Cambio</th>
                                <th><i class="bi bi-person me-1"></i>Empleado</th>
                                <th class="text-center"><i class="bi bi-info-circle me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historial as $mov)
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($mov->fecha)->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($mov->es_entrada)
                                            <span class="badge badge-entrada">
                                                <i class="bi bi-arrow-up me-1"></i>{{ $mov->tipo_movimiento_formateado }}
                                            </span>
                                        @else
                                            <span class="badge badge-salida">
                                                <i class="bi bi-arrow-down me-1"></i>{{ $mov->tipo_movimiento_formateado }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($mov->es_entrada)
                                            <span class="text-success fw-bold">+{{ abs($mov->cantidad_cambio) }}</span>
                                        @else
                                            <span class="text-danger fw-bold">-{{ abs($mov->cantidad_cambio) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $empleado = null;
                                            if ($mov->compra && $mov->compra->empleado) {
                                                $empleado = $mov->compra->empleado;
                                            } elseif ($mov->venta && $mov->venta->empleado) {
                                                $empleado = $mov->venta->empleado;
                                            } elseif ($mov->liquidacion && $mov->liquidacion->empleado) {
                                                $empleado = $mov->liquidacion->empleado;
                                            }
                                        @endphp
                                        @if($empleado)
                                            <small>{{ $empleado->nombre }}</small>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($empleado->rol) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('historial-stock.por-tipo', $mov->tipo_movimiento) }}" 
                                               class="btn btn-outline-info btn-sm" title="Ver movimientos del mismo tipo">
                                                <i class="bi bi-tag"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="mt-2 text-muted">No se encontraron movimientos para este producto.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($historial->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $historial->links() }}
                    </div>
                @endif

                <!-- Resumen de Resultados -->
                @if($historial->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-1"></i>Resumen del Producto
                                </h6>
                                <p class="mb-0">
                                    Mostrando {{ $historial->firstItem() }} - {{ $historial->lastItem() }} 
                                    de {{ $historial->total() }} movimientos encontrados para este producto.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-submit cuando cambie la fecha de inicio
        document.getElementById('fecha_inicio').addEventListener('change', function() {
            if (this.value && !document.getElementById('fecha_fin').value) {
                document.getElementById('fecha_fin').value = this.value;
            }
        });
    </script>
</body>
</html>