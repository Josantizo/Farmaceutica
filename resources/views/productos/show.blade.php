@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Detalle de Producto</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nombre</dt>
                        <dd class="col-sm-8">{{ $producto->nombre }}</dd>

                        <dt class="col-sm-4">Descripción</dt>
                        <dd class="col-sm-8">{{ $producto->descripcion }}</dd>

                        <dt class="col-sm-4">Precio compra</dt>
                        <dd class="col-sm-8">{{ number_format($producto->precio_compra, 2) }}</dd>

                        <dt class="col-sm-4">Precio venta</dt>
                        <dd class="col-sm-8">{{ number_format($producto->precio_venta, 2) }}</dd>

						<dt class="col-sm-4">Stock actual</dt>
						<dd class="col-sm-8">{{ $producto->stock_actual }}</dd>

                        <dt class="col-sm-4">Vence</dt>
                        <dd class="col-sm-8">{{ optional($producto->fecha_vencimiento)->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Lote</dt>
                        <dd class="col-sm-8">{{ $producto->lote }}</dd>

                        <dt class="col-sm-4">Categoría</dt>
                        <dd class="col-sm-8">{{ $producto->categoria->nombre ?? '-' }}</dd>

                        <dt class="col-sm-4">Proveedor</dt>
                        <dd class="col-sm-8">{{ $producto->proveedor->nombre ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Alertas</h5>
                    @if ($producto->alertas->isEmpty())
                        <p class="text-muted mb-0">Sin alertas.</p>
                    @else
                        <ul class="mb-0">
                            @foreach ($producto->alertas as $alerta)
                                <li>
                                    <strong>{{ ucfirst($alerta->tipo_alerta) }}</strong> -
                                    <span>{{ $alerta->estado }}</span>
                                    <span class="text-muted">({{ \\Carbon\\Carbon::parse($alerta->fecha_generada)->format('d/m/Y') }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Historial de stock</h5>
            @if ($producto->historialStock->isEmpty())
                <p class="text-muted mb-0">Sin movimientos.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th class="text-end">Cantidad</th>
                                <th class="text-end">Stock resultante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($producto->historialStock as $mov)
                                <tr>
                                    <td>{{ \\Carbon\\Carbon::parse($mov->fecha)->format('d/m/Y H:i') }}</td>
                                    <td>{{ ucfirst($mov->tipo_movimiento) }}</td>
                                    <td class="text-end">{{ $mov->cantidad }}</td>
                                    <td class="text-end">{{ $mov->stock_resultante }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


