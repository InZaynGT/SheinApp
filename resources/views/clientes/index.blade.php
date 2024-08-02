@extends('adminlte::page')

@section('title', 'Listado de Clientes')

@section('content_header')
    <h1 class="m-0 text-dark">Listado de Clientes</h1>
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
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->nombre }}</td>
                <td>{{ $order->direccion }}</td>
                <td>{{ $order->telefono }}</td>
                <td>
                    <!-- Botón para abrir el modal de edición -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editClientModal"
                        data-id="{{ $order->id }}"
                        data-nombre="{{ $order->nombre }}"
                        data-direccion="{{ $order->direccion }}"
                        data-telefono="{{ $order->telefono }}"
                        data-type="{{ $order->tipo_cli }}">
                        Editar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <!-- Modal para Editar Cliente -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClientForm" method="POST" action="{{ route('cliente.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="clientId">

                        <div class="mb-3">
                            <label for="clientName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="clientName" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="clientAddress" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="clientAddress" name="direccion" >
                        </div>

                        <div class="mb-3">
                            <label for="clientPhone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="clientPhone" name="telefono" >
                        </div>

                        <div class="mb-3">
                            <label for="clientType" class="form-label">Tipo de Cliente</label>
                            <select id="clientType" name="tipo_cliente" class="form-select" required>
                                <option value="" disabled>Seleccionar tipo</option>
                                <option value="0">Entidad</option>
                                <option value="1">Hombre</option>
                                <option value="2">Mujer</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar Cliente -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClientForm" method="POST" action="{{ route('cliente.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="newClientName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="newClientName" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="newClientType" class="form-label">Tipo de Cliente</label>
                            <select id="newClientType" name="tipo_cliente" class="form-select" required>
                                <option value="" disabled>Seleccionar tipo</option>
                                <option value="0">Entidad</option>
                                <option value="1">Hombre</option>
                                <option value="2">Mujer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="newClientAddress" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="newClientAddress" name="direccion" required>
                        </div>

                        <div class="mb-3">
                            <label for="newClientPhone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="newClientPhone" name="telefono" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
