@php($title = 'Detalle Categoría')

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
				<a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-primary">Editar</a>
				<a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Información</div>
					<div class="card-body">
						<dl class="row mb-0">
							<dt class="col-sm-4">Nombre</dt>
							<dd class="col-sm-8">{{ $categoria->nombre }}</dd>
							<dt class="col-sm-4">Descripción</dt>
							<dd class="col-sm-8">{{ $categoria->descripcion }}</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Productos</div>
					<div class="card-body">
						@if ($categoria->productos->isEmpty())
							<p class="text-muted mb-0">Sin productos.</p>
						@else
							<ul class="mb-0">
								@foreach ($categoria->productos as $producto)
									<li>
										<a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a>
										<span class="text-muted">(Proveedor: {{ optional($producto->proveedor)->nombre ?? '-' }})</span>
									</li>
								@endforeach
							</ul>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


