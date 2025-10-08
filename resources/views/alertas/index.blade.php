@php($title = 'Alertas')

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
				<a href="{{ route('alertas.pendientes') }}" class="btn btn-warning">Pendientes</a>
				<a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Producto</th>
						<th>Estado</th>
						<th class="text-end">Acciones</th>
					</tr>
				</thead>
				<tbody>
					@forelse($alertas as $alerta)
						<tr>
							<td>{{ \Carbon\Carbon::parse($alerta->fecha_generada)->format('d/m/Y H:i') }}</td>
							<td>{{ ucfirst($alerta->tipo_alerta) }}</td>
							<td>
								@if($alerta->producto)
									<a href="{{ route('productos.show', $alerta->producto) }}">{{ $alerta->producto->nombre }}</a>
								@else
									-
								@endif
							</td>
							<td>{{ $alerta->estado }}</td>
							<td class="text-end">
								<form action="{{ route('alertas.resolver', $alerta) }}" method="POST" class="d-inline">
									@csrf
									@method('PATCH')
									<button class="btn btn-sm btn-success">Marcar Resuelta</button>
								</form>
								<form action="{{ route('alertas.pendiente', $alerta) }}" method="POST" class="d-inline">
									@csrf
									@method('PATCH')
									<button class="btn btn-sm btn-warning">Marcar Pendiente</button>
								</form>
								<form action="{{ route('alertas.destroy', $alerta) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar alerta?');">
									@csrf
									@method('DELETE')
									<button class="btn btn-sm btn-danger">Eliminar</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center">No hay alertas.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="d-flex justify-content-center">
			{{ $alertas->links() }}
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


