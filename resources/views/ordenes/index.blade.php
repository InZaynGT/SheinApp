@extends('adminlte::page')

@section('title', 'Listado de Órdenes')

@section('content_header')
    <h1 class="m-0 text-dark">Listado de Órdenes</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Tabla de Órdenes -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Orden</th>
                <th>Nombre del Cliente</th>
                <th>Monto de la Orden</th>
                <th>Saldo Pendiente</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <tr>
                <th><input type="text" id="search-numero-orden" class="form-control" placeholder="ID"></th>
                <th><input type="text" id="search-promesa-entrega" class="form-control" placeholder="Fecha"></th>
                <th><input type="text" id="search-nombre-cliente" class="form-control" placeholder="Nombre Cliente"></th>
                <th><input type="text" id="search-monto-orden" class="form-control" placeholder="Monto de la Orden"></th>
                <th><input type="text" id="search-saldo-pendiente" class="form-control" placeholder="Saldo de la Orden"></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="ordenes-table">
            @foreach($ordenes as $order)
            <tr>
                <td style="width: 100px; padding: 10px;">{{ $order->id }}</td>
                <td>{{ \Carbon\Carbon::parse($order->fechaPromesa)->format('d F Y') }}</td>
                <td>{{ $order->cliente->nombre }}</td>
                <td>Q. {{ number_format($order->CXC->montoDocto,2) }}</td>
                <td>Q. {{ number_format($order->CXC->saldoDocto,2) }}</td>
                <td>
                    @switch($order->estado)
                        @case(0)
                            <span class="badge badge-warning">Solicitado</span>
                            @break
                        @case(1)
                            <span class="badge badge-danger">Cancelado</span>
                            @break
                        @case(2)
                            <span class="badge badge-success">Confirmado</span>
                            @break
                        @case(3)
                            <span class="badge badge-primary">En Bodega</span>
                            @break
                        @case(4)
                            <span class="badge badge-orange" style="background-color: orange;">Parcialmente pagado</span>
                            @break
                        @case(5)
                            <i class="fas fa-check text-success"></i>
                            @break
                    @endswitch
                </td>
                <td>
                    <a href="{{ route('ordenes.show', $order->id) }}" class="btn btn-info" target="_blank"><i class="fas fa-list"></i></a>
                    <a href="{{ route('voting-result', $order->id) }}" class="btn btn-warning" target="_blank"><i class="fas fa-print"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $ordenes->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("search-numero-orden").value = '';
    document.getElementById("search-nombre-cliente").value = '';
    document.getElementById("search-monto-orden").value = '';
    document.getElementById("search-saldo-pendiente").value = '';
    document.getElementById("search-promesa-entrega").value = '';

    function filterTable() {
        var numeroOrden = document.getElementById('search-numero-orden').value.toLowerCase();
        var nombreCliente = document.getElementById('search-nombre-cliente').value.toLowerCase();
        var montoOrden = document.getElementById('search-monto-orden').value.toLowerCase();
        var saldoPendiente = document.getElementById('search-saldo-pendiente').value.toLowerCase();
        var promesaEntrega = document.getElementById('search-promesa-entrega').value.toLowerCase();

        var table = document.getElementById('ordenes-table');
        var rows = table.getElementsByTagName('tr');

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName('td');
            var showRow = true;

            if (numeroOrden && cells[0].innerText.toLowerCase().indexOf(numeroOrden) === -1) {
                showRow = false;
            }
            if (nombreCliente && cells[1].innerText.toLowerCase().indexOf(nombreCliente) === -1) {
                showRow = false;
            }
            if (montoOrden && cells[2].innerText.toLowerCase().indexOf(montoOrden) === -1) {
                showRow = false;
            }
            if (saldoPendiente && cells[3].innerText.toLowerCase().indexOf(saldoPendiente) === -1) {
                showRow = false;
            }
            if (promesaEntrega && cells[4].innerText.toLowerCase().indexOf(promesaEntrega) === -1) {
                showRow = false;
            }

            rows[i].style.display = showRow ? '' : 'none';
        }
    }

    document.getElementById('search-numero-orden').addEventListener('keyup', filterTable);
    document.getElementById('search-nombre-cliente').addEventListener('keyup', filterTable);
    document.getElementById('search-monto-orden').addEventListener('keyup', filterTable);
    document.getElementById('search-saldo-pendiente').addEventListener('keyup', filterTable);
    document.getElementById('search-promesa-entrega').addEventListener('keyup', filterTable);
});
</script>
@stop
