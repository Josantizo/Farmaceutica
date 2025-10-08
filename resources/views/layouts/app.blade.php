<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sistema Farmacéutico</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<div class="container">
			<a class="navbar-brand" href="{{ route('dashboard') }}">
				<i class="fas fa-pills me-2"></i>Sistema Farmacéutico
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('productos.index') }}">
							<i class="fas fa-box me-1"></i>Productos
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('compras.index') }}">
							<i class="fas fa-shopping-cart me-1"></i>Compras
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('ventas.index') }}">
							<i class="fas fa-cash-register me-1"></i>Ventas
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('clientes.index') }}">
							<i class="fas fa-users me-1"></i>Clientes
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('proveedores.index') }}">
							<i class="fas fa-truck me-1"></i>Proveedores
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('empleados.index') }}">
							<i class="fas fa-user-tie me-1"></i>Empleados
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('categorias.index') }}">
							<i class="fas fa-tags me-1"></i>Categorías
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('alertas.index') }}">
							<i class="fas fa-exclamation-triangle me-1"></i>Alertas
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('liquidaciones.index') }}">
							<i class="fas fa-receipt me-1"></i>Liquidaciones
						</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					@if(session('empleado_id'))
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
								<i class="fas fa-user me-1"></i>{{ session('empleado_nombre') }}
								<span class="badge bg-secondary ms-1">{{ session('empleado_rol') }}</span>
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
								</a></li>
							</ul>
						</li>
					@else
						<li class="nav-item">
							<a class="nav-link" href="{{ route('login') }}">
								<i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
							</a>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		@csrf
	</form>

	<div class="container mt-4">
		@if(session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
		@endif
		@if(session('error'))
			<div class="alert alert-danger">{{ session('error') }}</div>
		@endif
		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@yield('content')
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


