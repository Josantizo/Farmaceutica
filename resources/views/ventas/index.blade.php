@php($title = 'Ventas')

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
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">Nueva venta</a>
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
                <th>Cliente</th>
                <th>Empleado</th>
                <th>Total</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->ventas_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m-d') }}</td>
                    <td>{{ optional($venta->cliente)->nombre }}</td>
                    <td>{{ optional($venta->empleado)->nombre }}</td>
                    <td>{{ number_format($venta->total, 2) }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-secondary" href="{{ route('ventas.show', $venta) }}">Ver</a>
                        <a class="btn btn-sm btn-warning" href="{{ route('ventas.edit', $venta) }}">Editar</a>
                        <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar venta?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay ventas registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $ventas->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


