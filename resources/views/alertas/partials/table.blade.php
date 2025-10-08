<div class="table-responsive">
	<table class="table table-striped table-hover align-middle">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Tipo</th>
				<th>Producto</th>
				<th>Estado</th>
				<th class="text-end">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@forelse($alertas as $alerta)
				<tr>
					<td>{{ \Carbon\Carbon::parse($alerta->fecha_generada)->format('d/m/Y H:i') }}</td>
					<td>{{ ucfirst($alerta->tipo_alerta) }}</td>
					<td>
						@if($alerta->producto)
							<a href="{{ route('productos.show', $alerta->producto) }}">{{ $alerta->producto->nombre }}</a>
						@else
							-
						@endif
					</td>
					<td>{{ $alerta->estado }}</td>
					<td class="text-end">
						<form action="{{ route('alertas.resolver', $alerta) }}" method="POST" class="d-inline">
							@csrf
							@method('PATCH')
							<button class="btn btn-sm btn-success">Marcar Resuelta</button>
						</form>
						<form action="{{ route('alertas.pendiente', $alerta) }}" method="POST" class="d-inline">
							@csrf
							@method('PATCH')
							<button class="btn btn-sm btn-warning">Marcar Pendiente</button>
						</form>
						<form action="{{ route('alertas.destroy', $alerta) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar alerta?');">
							@csrf
							@method('DELETE')
							<button class="btn btn-sm btn-danger">Eliminar</button>
						</form>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="5" class="text-center">No hay alertas.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</div>


