@extends('adminlte::page')

@section('title', 'Listado de Movimientos')

@section('content_header')
    <h1 class="m-0 text-dark">Listado de Movimientos</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClientModal">
                Agregar Movimiento
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
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bancos as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

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
