@php($title = 'Nueva Categoría')

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
		<h1 class="h3 mb-4">{{ $title }}</h1>

		@if ($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('categorias.store') }}" class="row g-3">
			@csrf
			<div class="col-md-6">
				<label class="form-label">Nombre</label>
				<input name="nombre" type="text" value="{{ old('nombre') }}" required maxlength="45" class="form-control" />
			</div>
			<div class="col-md-6">
				<label class="form-label">Descripción</label>
				<input name="descripcion" type="text" value="{{ old('descripcion') }}" required maxlength="45" class="form-control" />
			</div>
			<div class="col-12 d-flex gap-2">
				<a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
				<button type="submit" class="btn btn-primary">Guardar</button>
			</div>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


