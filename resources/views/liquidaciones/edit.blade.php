@php($title = 'Editar Liquidación #'.$liquidacion->liquidacion_id)
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
            <a href="{{ route('liquidaciones.show', $liquidacion) }}" class="btn btn-secondary">Volver</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="POST" action="{{ route('liquidaciones.update', $liquidacion) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        @foreach(['borrador','enviada','aceptada','rechazada'] as $estado)
                            <option value="{{ $estado }}" @selected($liquidacion->estado === $estado)>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control" value="{{ old('observaciones', $liquidacion->observaciones) }}" />
                </div>

                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th style="width:140px">Cantidad</th>
                                    <th style="width:160px">Precio unitario</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($liquidacion->detalles as $i => $d)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $i }}][producto_id]" class="form-select">
                                                @foreach($productos as $p)
                                                    <option value="{{ $p->producto_id }}" @selected($p->producto_id === $d->producto_id)>{{ $p->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="items[{{ $i }}][detalle_liquidacion_id]" value="{{ $d->detalle_liquidacion_id }}" />
                                        </td>
                                        <td><input type="number" name="items[{{ $i }}][cantidad]" min="1" class="form-control" value="{{ $d->cantidad }}" /></td>
                                        <td><input type="number" step="0.01" name="items[{{ $i }}][precio_unitario]" class="form-control" value="{{ $d->precio_unitario }}" /></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

