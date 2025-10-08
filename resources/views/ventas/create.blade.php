@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Nueva Venta</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al dashboard</a>
    </div>

    <form method="POST" action="{{ route('ventas.store') }}" id="venta-form">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Cliente</label>
                <div class="input-group">
                    <select name="cliente_id" id="cliente-select" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->cliente_id }}" data-nit="{{ $cliente->nit ?? '' }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalCliente">Nuevo</button>
                </div>
                <div class="form-text">NIT: <span id="cliente-nit">—</span></div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Empleado</label>
                <select name="empleado_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->empleados_id }}">{{ $empleado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de venta</label>
                <input type="date" name="fecha_venta" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h5 m-0">Productos</h2>
            <button class="btn btn-sm btn-outline-primary" type="button" id="agregar-producto">Agregar producto</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="tabla-productos">
                <thead class="table-light">
                <tr>
                    <th style="width: 40%">Producto</th>
                    <th style="width: 15%">Precio</th>
                    <th style="width: 15%">Cantidad</th>
                    <th style="width: 15%">Subtotal</th>
                    <th style="width: 15%"></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th id="total-general">0.00</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary" type="submit">Guardar venta</button>
        </div>
    </form>
</div>

<template id="fila-template">
    <tr>
        <td>
            <select class="form-select producto-select" required>
                <option value="">Seleccione...</option>
                @foreach($productos as $p)
                    <option value="{{ $p->producto_id }}" data-precio="{{ $p->precio_venta }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
            <input type="hidden" class="producto-id-input" name="productos[IDX][producto_id]">
        </td>
        <td>
            <input type="number" step="0.01" min="0" class="form-control precio-input" name="productos[IDX][precio_unitario]" required>
        </td>
        <td>
            <input type="number" min="1" class="form-control cantidad-input" name="productos[IDX][cantidad]" value="1" required>
        </td>
        <td class="subtotal-td">0.00</td>
        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger eliminar-fila">Quitar</button></td>
    </tr>
    </template>

<script>
    const tablaBody = document.querySelector('#tabla-productos tbody');
    const agregarBtn = document.getElementById('agregar-producto');
    const filaTpl = document.getElementById('fila-template').content;
    const totalGeneralEl = document.getElementById('total-general');

    function recalcularTotales() {
        let total = 0;
        tablaBody.querySelectorAll('tr').forEach(tr => {
            const precio = parseFloat(tr.querySelector('.precio-input').value || '0');
            const cantidad = parseInt(tr.querySelector('.cantidad-input').value || '0');
            const subtotal = precio * cantidad;
            tr.querySelector('.subtotal-td').textContent = subtotal.toFixed(2);
            total += subtotal;
        });
        totalGeneralEl.textContent = total.toFixed(2);
    }

    function renumerarIndices() {
        tablaBody.querySelectorAll('tr').forEach((tr, idx) => {
            tr.querySelectorAll('input, select').forEach(el => {
                if (el.name?.includes('productos[')) {
                    el.name = el.name.replace(/productos\[[0-9]+\]/, `productos[${idx}]`);
                }
            });
        });
    }

    function agregarFila() {
        const clone = document.importNode(filaTpl, true);
        tablaBody.appendChild(clone);
        renumerarIndices();
        recalcularTotales();
    }

    agregarBtn.addEventListener('click', agregarFila);

    tablaBody.addEventListener('change', (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;
        if (e.target.classList.contains('producto-select')) {
            const opt = e.target.selectedOptions[0];
            const precio = parseFloat(opt?.dataset?.precio || '0');
            tr.querySelector('.precio-input').value = precio.toFixed(2);
            tr.querySelector('.producto-id-input').value = e.target.value;
        }
        recalcularTotales();
    });

    tablaBody.addEventListener('input', (e) => {
        if (e.target.matches('.precio-input, .cantidad-input')) {
            recalcularTotales();
        }
    });

    tablaBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('eliminar-fila')) {
            e.target.closest('tr').remove();
            renumerarIndices();
            recalcularTotales();
        }
    });

    // Agrega una fila inicial
    agregarFila();

    // Cliente: mostrar NIT al seleccionar
    const clienteSelect = document.getElementById('cliente-select');
    const clienteNitSpan = document.getElementById('cliente-nit');
    clienteSelect?.addEventListener('change', () => {
        const opt = clienteSelect.selectedOptions[0];
        clienteNitSpan.textContent = opt?.dataset?.nit || '—';
    });
</script>

<!-- Modal Nuevo Cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-nuevo-cliente">
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">NIT</label>
            <input type="text" name="nit" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
          </div>
        </form>
        <div class="alert alert-danger d-none" id="cliente-error"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="guardar-cliente">Guardar</button>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('guardar-cliente')?.addEventListener('click', async () => {
      const form = document.getElementById('form-nuevo-cliente');
      const data = Object.fromEntries(new FormData(form).entries());
      const res = await fetch('{{ route('clientes.store') }}', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: new FormData(form)
      });
      if (!res.ok) {
        const errBox = document.getElementById('cliente-error');
        errBox.classList.remove('d-none');
        try {
          const j = await res.json();
          errBox.textContent = j.message || 'Error al crear cliente';
        } catch { errBox.textContent = 'Error al crear cliente'; }
        return;
      }
      const { cliente } = await res.json();
      const sel = document.getElementById('cliente-select');
      const opt = document.createElement('option');
      opt.value = cliente.cliente_id;
      opt.textContent = cliente.nombre;
      opt.dataset.nit = cliente.nit || '';
      sel.appendChild(opt);
      sel.value = cliente.cliente_id;
      document.getElementById('cliente-nit').textContent = cliente.nit || '—';
      const modalEl = document.getElementById('modalCliente');
      const m = bootstrap.Modal.getOrCreateInstance(modalEl);
      m.hide();
      form.reset();
    });
  </script>
</div>


