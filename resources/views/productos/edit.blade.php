@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Editar Producto</h1>

	@if ($errors->any())
		<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
			<ul class="list-disc pl-5">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

    <form method="POST" action="{{ route('productos.update', $producto) }}" class="row g-3">
		@csrf
		@method('PUT')
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre</label>
            <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $producto->nombre) }}" required maxlength="45" class="form-control" />
        </div>

        <div class="col-md-6">
            <label for="descripcion" class="form-label">Descripción</label>
            <input id="descripcion" name="descripcion" type="text" value="{{ old('descripcion', $producto->descripcion) }}" required maxlength="45" class="form-control" />
        </div>

        <div class="col-md-4">
            <label for="precio_compra" class="form-label">Precio de compra</label>
            <input id="precio_compra" name="precio_compra" type="number" step="0.01" min="0" value="{{ old('precio_compra', $producto->precio_compra) }}" required class="form-control" />
        </div>
        <div class="col-md-4">
            <label for="precio_venta" class="form-label">Precio de venta</label>
            <input id="precio_venta" name="precio_venta" type="number" step="0.01" min="0" value="{{ old('precio_venta', $producto->precio_venta) }}" required class="form-control" />
        </div>
        <div class="col-md-4">
            <label for="stock_actual" class="form-label">Stock actual</label>
            <input id="stock_actual" name="stock_actual" type="number" step="1" min="0" value="{{ old('stock_actual', $producto->stock_actual) }}" required class="form-control" />
        </div>

        <div class="col-md-4">
            <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento</label>
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="date" value="{{ old('fecha_vencimiento', optional($producto->fecha_vencimiento)->format('Y-m-d')) }}" required class="form-control" />
        </div>
        <div class="col-md-4">
            <label for="lote" class="form-label">Lote</label>
            <input id="lote" name="lote" type="text" maxlength="45" value="{{ old('lote', $producto->lote) }}" required class="form-control" />
        </div>
        <div class="col-md-4">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select id="categoria_id" name="categoria_id" required class="form-select">
                <option value="">Seleccione una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->categoria_id }}" @selected(old('categoria_id', $producto->categoria_id)==$categoria->categoria_id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="proveedor_id" class="form-label">Proveedor</label>
            <select id="proveedor_id" name="proveedor_id" required class="form-select">
                <option value="">Seleccione un proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->proveedor_id }}" @selected(old('proveedor_id', $producto->proveedor_id)==$proveedor->proveedor_id)>{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 d-flex gap-2 pt-2">
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
	</form>
</div>
@endsection


