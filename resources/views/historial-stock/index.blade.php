@php($title = 'Historial de Stock')

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
        .btn-filter {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        .btn-filter:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0 text-dark">
                        <i class="bi bi-clock-history me-2"></i>{{ $title }}
                    </h1>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-1"></i>Dashboard
                    </a>
                </div>

                <!-- Panel de Filtros -->
                <div class="filter-card">
                    <form method="GET" action="{{ route('historial-stock.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="producto_id" class="form-label">Producto</label>
                                <select class="form-select" id="producto_id" name="producto_id">
                                    <option value="">Todos los productos</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->producto_id }}" 
                                            {{ request('producto_id') == $producto->producto_id ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
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
                                <label for="proveedor_id" class="form-label">Proveedor</label>
                                <select class="form-select" id="proveedor_id" name="proveedor_id">
                                    <option value="">Todos los proveedores</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->proveedor_id }}" 
                                            {{ request('proveedor_id') == $proveedor->proveedor_id ? 'selected' : '' }}>
                                            {{ $proveedor->nombre }}
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
                            
                            <div class="col-md-3">
                                <label for="precio_min" class="form-label">Precio Mínimo</label>
                                <input type="number" class="form-control" id="precio_min" name="precio_min" 
                                       step="0.01" min="0" value="{{ request('precio_min') }}" placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search me-1"></i>Filtrar
                                </button>
                                <a href="{{ route('historial-stock.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i>Limpiar
                                </a>
                                <button type="button" class="btn btn-success" onclick="exportarFiltros()">
                                    <i class="bi bi-download me-1"></i>Exportar
                                </button>
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
                                <th><i class="bi bi-box me-1"></i>Producto</th>
                                <th><i class="bi bi-tag me-1"></i>Tipo</th>
                                <th class="text-end"><i class="bi bi-arrow-up-down me-1"></i>Cambio</th>
                                <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                                <th><i class="bi bi-person me-1"></i>Empleado</th>
                                <th><i class="bi bi-building me-1"></i>Proveedor</th>
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
                                        @if($mov->producto)
                                            <strong>{{ $mov->producto->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">Stock actual: {{ $mov->producto->stock_actual }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
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
                                    <td class="text-end">
                                        @if($mov->producto)
                                            <small class="text-muted">Compra:</small> ${{ number_format($mov->producto->precio_compra, 2) }}
                                            <br>
                                            <small class="text-muted">Venta:</small> ${{ number_format($mov->producto->precio_venta, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
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
                                    <td>
                                        @if($mov->producto && $mov->producto->proveedor)
                                            <small>{{ $mov->producto->proveedor->nombre }}</small>
                                        @elseif($mov->compra && $mov->compra->proveedor)
                                            <small>{{ $mov->compra->proveedor->nombre }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($mov->producto)
                                                <a href="{{ route('historial-stock.por-producto', $mov->producto->producto_id) }}" 
                                                   class="btn btn-outline-primary btn-sm" title="Ver historial del producto">
                                                    <i class="bi bi-box"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('historial-stock.por-tipo', $mov->tipo_movimiento) }}" 
                                               class="btn btn-outline-info btn-sm" title="Ver movimientos del mismo tipo">
                                                <i class="bi bi-tag"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="mt-2 text-muted">No se encontraron movimientos con los filtros aplicados.</p>
                                        <a href="{{ route('historial-stock.index') }}" class="btn btn-outline-primary">
                                            Ver todos los movimientos
                                        </a>
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
                                    <i class="bi bi-info-circle me-1"></i>Resumen
                                </h6>
                                <p class="mb-0">
                                    Mostrando {{ $historial->firstItem() }} - {{ $historial->lastItem() }} 
                                    de {{ $historial->total() }} movimientos encontrados.
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
        function exportarFiltros() {
            // Crear un formulario temporal para exportar
            const form = document.getElementById('filterForm');
            const exportForm = form.cloneNode(true);
            exportForm.action = '{{ route("historial-stock.exportar") }}';
            exportForm.method = 'POST';
            
            // Agregar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            exportForm.appendChild(csrfToken);
            
            document.body.appendChild(exportForm);
            exportForm.submit();
            document.body.removeChild(exportForm);
        }

        // Auto-submit cuando cambie la fecha de inicio
        document.getElementById('fecha_inicio').addEventListener('change', function() {
            if (this.value && !document.getElementById('fecha_fin').value) {
                document.getElementById('fecha_fin').value = this.value;
            }
        });
    </script>
</body>
</html>