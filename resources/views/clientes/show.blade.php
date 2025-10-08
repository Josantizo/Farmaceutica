@php($title = 'Detalle Cliente')

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
				<a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary">Editar</a>
				<a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Información</div>
					<div class="card-body">
						<dl class="row mb-0">
							<dt class="col-sm-4">Nombre</dt>
							<dd class="col-sm-8">{{ $cliente->nombre }}</dd>
							<dt class="col-sm-4">NIT</dt>
							<dd class="col-sm-8">{{ $cliente->nit }}</dd>
							<dt class="col-sm-4">Dirección</dt>
							<dd class="col-sm-8">{{ $cliente->direccion }}</dd>
							<dt class="col-sm-4">Teléfono</dt>
							<dd class="col-sm-8">{{ $cliente->telefono }}</dd>
							<dt class="col-sm-4">Correo</dt>
							<dd class="col-sm-8">{{ $cliente->correo }}</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Ventas</div>
					<div class="card-body">
						@if ($cliente->ventas->isEmpty())
							<p class="text-muted mb-0">Sin ventas.</p>
						@else
							<div class="table-responsive">
								<table class="table table-sm">
									<thead>
										<tr>
											<th>Fecha</th>
											<th class="text-end">Total ítems</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($cliente->ventas as $venta)
											<tr>
												<td>{{ optional($venta->fecha)->format('d/m/Y H:i') }}</td>
												<td class="text-end">{{ $venta->detalleVentas->sum('cantidad') }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


