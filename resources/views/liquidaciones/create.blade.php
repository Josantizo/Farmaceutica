@php($title = 'Nueva Liquidación')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">{{ $title }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('liquidaciones.index') }}" class="btn btn-secondary">Volver</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Proveedor</label>
                    <select name="proveedor_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($proveedores as $prov)
                            <option value="{{ $prov->proveedor_id }}" @selected(optional($proveedorSeleccionado)->proveedor_id === $prov->proveedor_id)>{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @if($proveedorSeleccionado)
                <div class="col-md-6">
                    <label class="form-label">Política (días)</label>
                    <input class="form-control" value="{{ optional($proveedorSeleccionado->politica)->dias_anticipacion ?? 30 }}" disabled />
                </div>
                @endif
            </form>
        </div>
    </div>

    @if($proveedorSeleccionado)
    <form method="POST" action="{{ route('liquidaciones.store') }}">
        @csrf
        <input type="hidden" name="proveedor_id" value="{{ $proveedorSeleccionado->proveedor_id }}" />
        <input type="hidden" name="fecha_liquidacion" value="{{ now()->format('Y-m-d H:i:s') }}" />

        <div class="card">
            <div class="card-header">Productos sugeridos por vencer</div>
            <div class="card-body">
                @if($sugeridos->isEmpty())
                    <p class="text-muted mb-0">No hay productos por vencer según la política.</p>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Vence</th>
                                    <th>Stock</th>
                                    <th style="width:140px">Cantidad</th>
                                    <th style="width:160px">Precio unitario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sugeridos as $i => $prod)
                                    <tr>
                                        <td>{{ $prod->nombre }}</td>
                                        <td>{{ optional($prod->fecha_vencimiento)->format('d/m/Y') }}</td>
                                        <td>{{ $prod->stock_actual }}</td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][cantidad]" class="form-control" min="0" max="{{ $prod->stock_actual }}" value="0">
                                            <input type="hidden" name="items[{{ $i }}][producto_id]" value="{{ $prod->producto_id }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[{{ $i }}][precio_unitario]" class="form-control" value="{{ $prod->precio_compra }}">
                                            <input type="hidden" name="items[{{ $i }}][motivo]" value="por_vencer">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Crear liquidación</button>
            </div>
        </div>
    </form>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

