@php($title = 'Alertas por tipo')

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
			<h1 class="h3 m-0">{{ $title }}: {{ ucfirst($tipo) }}</h1>
			<div class="d-flex gap-2">
				<a href="{{ route('alertas.index') }}" class="btn btn-secondary">Todas</a>
			</div>
		</div>

		@include('alertas.partials.table', ['alertas' => $alertas])

		<div class="d-flex justify-content-center mt-3">
			{{ $alertas->links() }}
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


