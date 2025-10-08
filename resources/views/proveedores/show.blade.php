@php($title = 'Detalle Proveedor')

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
				<a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-primary">Editar</a>
				<a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Volver</a>
				<a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Información</div>
					<div class="card-body">
						<dl class="row mb-0">
							<dt class="col-sm-4">Nombre</dt>
							<dd class="col-sm-8">{{ $proveedor->nombre }}</dd>
							<dt class="col-sm-4">NIT</dt>
							<dd class="col-sm-8">{{ $proveedor->nit }}</dd>
							<dt class="col-sm-4">Dirección</dt>
							<dd class="col-sm-8">{{ $proveedor->direccion }}</dd>
							<dt class="col-sm-4">Teléfono</dt>
							<dd class="col-sm-8">{{ $proveedor->telefono }}</dd>
							<dt class="col-sm-4">Correo</dt>
							<dd class="col-sm-8">{{ $proveedor->correo }}</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Productos</div>
					<div class="card-body">
						@if ($proveedor->productos->isEmpty())
							<p class="text-muted mb-0">Sin productos.</p>
						@else
							<ul class="mb-0">
								@foreach ($proveedor->productos as $producto)
									<li>
										<a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a>
										<span class="text-muted">(Stock: {{ $producto->stock_actual }})</span>
									</li>
								@endforeach
							</ul>
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3 mt-1">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Política de vencimiento del proveedor</div>
					<div class="card-body">
						@if(session('success'))
							<div class="alert alert-success">{{ session('success') }}</div>
						@endif
						<form method="POST" action="{{ route('proveedores.politica.upsert', $proveedor) }}" class="row g-3">
							@csrf
							<div class="col-md-3">
								<label class="form-label">Días de anticipación</label>
								<input type="number" name="dias_anticipacion" min="0" max="3650" class="form-control" value="{{ old('dias_anticipacion', optional($proveedor->politica)->dias_anticipacion ?? 30) }}" required />
							</div>
							<div class="col-md-3">
								<label class="form-label">Método de liquidación</label>
								<input type="text" name="metodo_liquidacion" maxlength="45" class="form-control" value="{{ old('metodo_liquidacion', optional($proveedor->politica)->metodo_liquidacion) }}" />
							</div>
							<div class="col-md-3">
								<label class="form-label">% crédito</label>
								<input type="number" step="0.01" min="0" max="100" name="porcentaje_credito" class="form-control" value="{{ old('porcentaje_credito', optional($proveedor->politica)->porcentaje_credito) }}" />
							</div>
							<div class="col-md-3">
								<label class="form-label">Producto (opcional)</label>
								<select name="producto_id" class="form-select">
									<option value="">— General —</option>
									@foreach ($proveedor->productos as $producto)
										<option value="{{ $producto->producto_id }}" @selected(old('producto_id', optional($proveedor->politica)->producto_id) == $producto->producto_id)>{{ $producto->nombre }}</option>
									@endforeach
								</select>
							</div>

							<div class="col-md-3">
								<label class="form-label">Última actualización</label>
								<input type="text" class="form-control" value="{{ optional($proveedor->politica && $proveedor->politica->fecha_actualizacion ? $proveedor->politica->fecha_actualizacion->format('d/m/Y H:i') : null) }}" disabled />
							</div>

							<div class="col-12">
								<button type="submit" class="btn btn-primary">Guardar política</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


