@php($title = 'Compras')

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
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al dashboard</a>
            <a href="{{ route('compras.create') }}" class="btn btn-primary">Nueva compra</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Empleado</th>
                <th>Total</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($compras as $compra)
                <tr>
                    <td>{{ $compra->compra_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('Y-m-d') }}</td>
                    <td>{{ optional($compra->proveedor)->nombre }}</td>
                    <td>{{ optional($compra->empleado)->nombre }}</td>
                    <td>{{ number_format($compra->total, 2) }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-secondary" href="{{ route('compras.show', $compra) }}">Ver</a>
                        <a class="btn btn-sm btn-warning" href="{{ route('compras.edit', $compra) }}">Editar</a>
                        <form action="{{ route('compras.destroy', $compra) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar compra?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay compras registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $compras->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


