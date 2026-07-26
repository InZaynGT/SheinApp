@extends('adminlte::page')

@section('title', 'Listado de Clientes')

@section('content_header')
    <h1 class="m-0 text-dark">Listado de Pagos</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClientModal">
                Agregar Cliente
            </button>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre">
        </div>
    </div>
    <table class="table table-striped" id="clientsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Cliente</th>
                <th>Forma de Pago</th>
                <th>Monto Cancelado</th>
                <th>Referencia</th>
                <th>N°. de Documento Bancario</th>
                <th>Cuenta Bancaria</th>
                <th>Ordenes a las que se aplicó</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listado_pagos as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->Clientes->nombre }}</td>
                <td>{{ $order->formaPago->nombre }}</td>
                <td>Q. {{ number_format($order->monto,2) }}</td>
                <td>{{ $order->referencia }}</td>
                <td>{{ $order->NRO_DOCTO_BANCARIO }}</td>
                <td>
                    {{ optional(optional($order->CuentasBancarias)->Bancos)->nombre . ' - '}}
                    {{ optional($order->CuentasBancarias)->nombre_cuenta }}
                </td>    
                <td>
                    @foreach($order->PagosDet as $pagoDet)
                        @if($pagoDet->orden)
                            <a target='_blank' href="{{ route('ordenes.show', $pagoDet->cxcDocumento->Nro_docto) }}">Orden #{{ $pagoDet->cxcDocumento->Nro_docto }}</a><br>
                        @endif
                    @endforeach
                </td> 
                <td>
                    <a href="{{ route('pago_impresion', $order->id) }}" class="btn btn-warning" target="_blank"><i class="fas fa-print"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editClientModal = document.getElementById('editClientModal');
        editClientModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var clientId = button.getAttribute('data-id');
            var clientName = button.getAttribute('data-nombre');
            var clientAddress = button.getAttribute('data-direccion');
            var clientPhone = button.getAttribute('data-telefono');
            var clientType = button.getAttribute('data-type');

            var clientIdInput = editClientModal.querySelector('#clientId');
            var clientNameInput = editClientModal.querySelector('#clientName');
            var clientAddressInput = editClientModal.querySelector('#clientAddress');
            var clientPhoneInput = editClientModal.querySelector('#clientPhone');
            var clientTypeInput = editClientModal.querySelector('#clientType');

            clientIdInput.value = clientId;
            clientNameInput.value = clientName;
            clientAddressInput.value = clientAddress;
            clientPhoneInput.value = clientPhone;
            clientTypeInput.value = clientType;
        });
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.getElementById('clientsTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var nameCell = rows[i].getElementsByTagName('td')[1];
                var DireccionCell = rows[i].getElementsByTagName('td')[2];
                var name = nameCell.textContent.toLowerCase();
                var Direccion = DireccionCell.textContent.toLowerCase();

                if (name.indexOf(searchValue) > -1 || Direccion.indexOf(searchValue) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    });
</script>
@stop
