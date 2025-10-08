@php($title = 'Liquidaciones')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>.table td,.table th{vertical-align: middle}</style>
    </head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">{{ $title }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('liquidaciones.create') }}" class="btn btn-primary">Nueva liquidación</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($liquidaciones as $liq)
                        <tr>
                            <td>{{ $liq->liquidacion_id }}</td>
                            <td>{{ optional($liq->proveedor)->nombre }}</td>
                            <td>{{ optional($liq->fecha_liquidacion)->format('d/m/Y H:i') }}</td>
                            <td><span class="badge text-bg-secondary">{{ $liq->estado }}</span></td>
                            <td class="text-end">${{ number_format($liq->total, 2) }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('liquidaciones.show', $liq) }}">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted p-4">Sin liquidaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $liquidaciones->links() }}</div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

