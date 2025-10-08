@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Productos</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al dashboard</a>
                <a href="{{ route('productos.create') }}" class="btn btn-primary">Nuevo producto</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Vencimiento</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>{{ number_format($producto->precio_compra, 2) }}</td>
                            <td>{{ number_format($producto->precio_venta, 2) }}</td>
                            <td>{{ $producto->stock_actual }}</td>
                            <td>{{ optional($producto->fecha_vencimiento)->format('Y-m-d') }}</td>
                            <td>{{ optional($producto->categoria)->nombre }}</td>
                            <td>{{ optional($producto->proveedor)->nombre }}</td>
                            <td class="text-end">
                                <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-secondary">Ver</a>
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $productos->links() }}
        </div>
    </div>
@endsection


