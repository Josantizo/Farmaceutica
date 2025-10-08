@php($title = 'Liquidación #'.$liquidacion->liquidacion_id)
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
            <a href="{{ route('liquidaciones.edit', $liquidacion) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('liquidaciones.index') }}" class="btn btn-secondary">Volver</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div><strong>Proveedor:</strong> {{ optional($liquidacion->proveedor)->nombre }}</div>
                    <div><strong>Fecha:</strong> {{ optional($liquidacion->fecha_liquidacion)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-4">
                    <div><strong>Estado:</strong> <span class="badge text-bg-secondary">{{ $liquidacion->estado }}</span></div>
                    <div><strong>Total:</strong> ${{ number_format($liquidacion->total, 2) }}</div>
                </div>
                <div class="col-md-4">
                    <div><strong>Observaciones:</strong> {{ $liquidacion->observaciones }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Detalles</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio unitario</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($liquidacion->detalles as $d)
                        <tr>
                            <td>{{ optional($d->producto)->nombre }}</td>
                            <td class="text-end">{{ $d->cantidad }}</td>
                            <td class="text-end">${{ number_format($d->precio_unitario, 2) }}</td>
                            <td class="text-end">${{ number_format($d->cantidad * $d->precio_unitario, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">${{ number_format($liquidacion->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

