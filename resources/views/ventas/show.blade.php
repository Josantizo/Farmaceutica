@php($title = 'Detalle de Venta')

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
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3">{{ $title }} #{{ $venta->ventas_id }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al dashboard</a>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card"><div class="card-body">
                <div class="fw-bold">Fecha</div>
                <div>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m-d') }}</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card"><div class="card-body">
                <div class="fw-bold">Cliente</div>
                <div>{{ optional($venta->cliente)->nombre }}</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card"><div class="card-body">
                <div class="fw-bold">Empleado</div>
                <div>{{ optional($venta->empleado)->nombre }}</div>
            </div></div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($venta->detalleVentas as $d)
                <tr>
                    <td>{{ optional($d->producto)->nombre }}</td>
                    <td>{{ number_format($d->precio_unitario, 2) }}</td>
                    <td>{{ $d->cantidad }}</td>
                    <td>{{ number_format($d->subtotal, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th>{{ number_format($venta->total, 2) }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


