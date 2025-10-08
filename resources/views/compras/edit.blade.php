@php($title = 'Editar Compra')

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
        <h1 class="h3 m-0">{{ $title }} #{{ $compra->compra_id }}</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Ir al dashboard</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('compras.update', $compra) }}" id="compra-form">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Proveedor</label>
                <select name="proveedor_id" class="form-select" required>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->proveedor_id }}" @selected($proveedor->proveedor_id == $compra->proveedor_id)>{{ $proveedor->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Empleado</label>
                <select name="empleado_id" class="form-select" required>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->empleados_id }}" @selected($empleado->empleados_id == $compra->empleado_id)>{{ $empleado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de compra</label>
                <input type="date" name="fecha_compra" class="form-control" value="{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('Y-m-d') }}" required>
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
                <tbody>
                @foreach($compra->detalleCompras as $i => $d)
                    <tr>
                        <td>
                            <select class="form-select producto-select" required>
                                @foreach($productos as $p)
                                    <option value="{{ $p->producto_id }}" data-precio="{{ $p->precio_compra }}" @selected($p->producto_id == $d->producto_id)>{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" class="producto-id-input" name="productos[{{ $i }}][producto_id]" value="{{ $d->producto_id }}">
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" class="form-control precio-input" name="productos[{{ $i }}][precio_unitario]" value="{{ $d->precio_unitario }}" required>
                        </td>
                        <td>
                            <input type="number" min="1" class="form-control cantidad-input" name="productos[{{ $i }}][cantidad]" value="{{ $d->cantidad }}" required>
                        </td>
                        <td class="subtotal-td">0.00</td>
                        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger eliminar-fila">Quitar</button></td>
                    </tr>
                @endforeach
                </tbody>
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
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary" type="submit">Guardar cambios</button>
        </div>
    </form>
</div>

<template id="fila-template">
    <tr>
        <td>
            <select class="form-select producto-select" required>
                <option value="">Seleccione...</option>
                @foreach($productos as $p)
                    <option value="{{ $p->producto_id }}" data-precio="{{ $p->precio_compra }}">{{ $p->nombre }}</option>
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

    // Inicializa subtotales
    recalcularTotales();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


