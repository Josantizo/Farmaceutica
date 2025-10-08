@php($title = 'Iniciar Sesión')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-3">Sistema Farmacéutico</h1>
                            <p class="text-muted">Iniciar Sesión</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" 
                                       class="form-control @error('usuario') is-invalid @enderror" 
                                       id="usuario" 
                                       name="usuario" 
                                       value="{{ old('usuario') }}" 
                                       required 
                                       autofocus>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <input type="password" 
                                       class="form-control @error('contraseña') is-invalid @enderror" 
                                       id="contraseña" 
                                       name="contraseña" 
                                       required>
                                @error('contraseña')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-link">Volver al Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
