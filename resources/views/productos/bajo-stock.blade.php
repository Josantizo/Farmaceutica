@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
	<div class="flex items-center justify-between mb-4">
		<h1 class="text-2xl font-bold">Productos con bajo stock</h1>
		<a href="{{ route('productos.index') }}" class="px-3 py-2 border rounded">Volver</a>
	</div>

	<div class="overflow-x-auto">
		<table class="min-w-full border">
			<thead class="bg-gray-100">
				<tr>
					<th class="px-3 py-2 text-left border">Nombre</th>
					<th class="px-3 py-2 text-left border">Categoría</th>
					<th class="px-3 py-2 text-left border">Proveedor</th>
					<th class="px-3 py-2 text-right border">Stock</th>
					<th class="px-3 py-2 text-right border">Vence</th>
					<th class="px-3 py-2 text-right border">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($productos as $producto)
					<tr>
						<td class="px-3 py-2 border">{{ $producto->nombre }}</td>
						<td class="px-3 py-2 border">{{ $producto->categoria->nombre ?? '-' }}</td>
						<td class="px-3 py-2 border">{{ $producto->proveedor->nombre ?? '-' }}</td>
						<td class="px-3 py-2 border text-right">{{ $producto->stock_actual }}</td>
						<td class="px-3 py-2 border text-right">{{ optional($producto->fecha_vencimiento)->format('d/m/Y') }}</td>
						<td class="px-3 py-2 border text-right">
							<a href="{{ route('productos.show', $producto) }}" class="text-blue-600">Ver</a>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" class="px-3 py-4 text-center text-gray-600">Sin productos.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<div class="mt-4">
		{{ $productos->links() }}
	</div>
</div>
@endsection


