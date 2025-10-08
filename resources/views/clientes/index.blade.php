@php($title = 'Clientes')

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
				<a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
				<a href="{{ route('clientes.create') }}" class="btn btn-primary">Nuevo cliente</a>
			</div>
		</div>

		@if(session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
		@endif

		<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>NIT</th>
						<th>Dirección</th>
						<th>Teléfono</th>
						<th>Correo</th>
						<th class="text-end">Acciones</th>
					</tr>
				</thead>
				<tbody>
					@forelse($clientes as $cliente)
						<tr>
							<td>{{ $cliente->nombre }}</td>
							<td>{{ $cliente->nit }}</td>
							<td>{{ $cliente->direccion }}</td>
							<td>{{ $cliente->telefono }}</td>
							<td>{{ $cliente->correo }}</td>
							<td class="text-end">
								<a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-secondary">Ver</a>
								<a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">Editar</a>
								<form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar cliente?');">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="text-center">No hay clientes.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="d-flex justify-content-center">
			{{ $clientes->links() }}
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


