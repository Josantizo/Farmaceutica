@php($title = 'Historial de Stock - Tipo: ' . ucfirst($tipo))

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
			<a href="{{ route('historial-stock.index') }}" class="btn btn-secondary">Todos</a>
		</div>

		<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Producto</th>
						<th class="text-end">Cantidad</th>
						<th class="text-end">Stock resultante</th>
					</tr>
				</thead>
				<tbody>
					@forelse($historial as $mov)
						<tr>
							<td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y H:i') }}</td>
							<td>
								@if($mov->producto)
									<a href="{{ route('historial-stock.por-producto', $mov->producto->producto_id) }}">{{ $mov->producto->nombre }}</a>
								@else - @endif
							</td>
							<td class="text-end">{{ $mov->cantidad }}</td>
							<td class="text-end">{{ $mov->stock_resultante }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center">Sin movimientos.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="d-flex justify-content-center">
			{{ $historial->links() }}
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


