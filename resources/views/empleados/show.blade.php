@php($title = 'Detalle Empleado')

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
				<a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-primary">Editar</a>
				<a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver</a>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Información</div>
					<div class="card-body">
						<dl class="row mb-0">
							<dt class="col-sm-4">Nombre</dt>
							<dd class="col-sm-8">{{ $empleado->nombre }}</dd>
							<dt class="col-sm-4">Usuario</dt>
							<dd class="col-sm-8">{{ $empleado->usuario }}</dd>
							<dt class="col-sm-4">Rol</dt>
							<dd class="col-sm-8">{{ $empleado->rol }}</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Actividad</div>
					<div class="card-body">
						<div class="row">
							<div class="col">
								<h6>Compras</h6>
								<p class="mb-0">{{ $empleado->compras->count() }}</p>
							</div>
							<div class="col">
								<h6>Ventas</h6>
								<p class="mb-0">{{ $empleado->ventas->count() }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


