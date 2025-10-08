@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
	<h1 class="text-2xl font-bold mb-6">Crear Producto</h1>

	@if ($errors->any())
		<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
			<ul class="list-disc pl-5">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form method="POST" action="{{ route('productos.store') }}" class="space-y-4">
		@csrf

		<div>
			<label for="nombre" class="block font-medium">Nombre</label>
			<input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required maxlength="45" class="w-full border rounded px-3 py-2" />
		</div>

		<div>
			<label for="descripcion" class="block font-medium">Descripción</label>
			<input id="descripcion" name="descripcion" type="text" value="{{ old('descripcion') }}" required maxlength="45" class="w-full border rounded px-3 py-2" />
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
			<div>
				<label for="precio_compra" class="block font-medium">Precio de compra</label>
				<input id="precio_compra" name="precio_compra" type="number" step="0.01" min="0" value="{{ old('precio_compra') }}" required class="w-full border rounded px-3 py-2" />
			</div>
			<div>
				<label for="precio_venta" class="block font-medium">Precio de venta</label>
				<input id="precio_venta" name="precio_venta" type="number" step="0.01" min="0" value="{{ old('precio_venta') }}" required class="w-full border rounded px-3 py-2" />
			</div>
			<div>
				<label for="stock_actual" class="block font-medium">Stock actual</label>
				<input id="stock_actual" name="stock_actual" type="number" step="1" min="0" value="{{ old('stock_actual') }}" required class="w-full border rounded px-3 py-2" />
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
			<div>
				<label for="fecha_vencimiento" class="block font-medium">Fecha de vencimiento</label>
				<input id="fecha_vencimiento" name="fecha_vencimiento" type="date" value="{{ old('fecha_vencimiento') }}" required class="w-full border rounded px-3 py-2" />
			</div>
			<div>
				<label for="lote" class="block font-medium">Lote</label>
				<input id="lote" name="lote" type="text" maxlength="45" value="{{ old('lote') }}" required class="w-full border rounded px-3 py-2" />
			</div>
			<div>
				<label for="categoria_id" class="block font-medium">Categoría</label>
				<select id="categoria_id" name="categoria_id" required class="w-full border rounded px-3 py-2">
					<option value="">Seleccione una categoría</option>
					@foreach ($categorias as $categoria)
						<option value="{{ $categoria->categoria_id }}" @selected(old('categoria_id')==$categoria->categoria_id)>{{ $categoria->nombre }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div>
			<label for="proveedor_id" class="block font-medium">Proveedor</label>
			<select id="proveedor_id" name="proveedor_id" required class="w-full border rounded px-3 py-2">
				<option value="">Seleccione un proveedor</option>
				@foreach ($proveedores as $proveedor)
					<option value="{{ $proveedor->proveedor_id }}" @selected(old('proveedor_id')==$proveedor->proveedor_id)>{{ $proveedor->nombre }}</option>
				@endforeach
			</select>
		</div>

		<div class="flex items-center gap-3 pt-2">
			<a href="{{ route('productos.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
			<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
		</div>
	</form>
</div>
@endsection


