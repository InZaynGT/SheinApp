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
                <th>Cuenta Bancaria</th>
                <th>Fecha</th>
                <th>Nro de Referencia</th>
                <th>Debe</th>
                <th>Haber</th>
                <th>Saldo Actual</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mov_banc as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->CuentaBancaria->Bancos->nombre." - ".$order->CuentaBancaria->numero_cuenta." - ".$order->CuentaBancaria->nombre_cuenta }}</td>
                <td>{{ $order->fecha }}</td>
                <td>{{ $order->nro_referencia }}</td>
                <td>Q. {{ number_format($order->debe,2) }}</td>
                <td>Q. {{ number_format($order->haber,2) }}</td>
                <td>Q. {{ number_format($order->saldoActual,2) }}</td>
                <td>{{ $order->notas }}</td>
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

 <!-- Modal para Agregar Movimiento -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Agregar Movimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMovementForm" method="POST" action="{{ route('movimiento.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="newCuentaBancaria" class="form-label">Cuenta Bancaria</label>
                        <select id="newCuentaBancaria" name="cuenta_bancaria" class="form-select" required>
                            <option value="" disabled selected>Seleccionar Cuenta Bancaria</option>
                            @foreach($cuentas_bancarias as $cuenta)
                                <option value="{{ $cuenta->id }}">
                                    {{ $cuenta->Bancos->nombre . " - " . $cuenta->nombre_cuenta . " - " . $cuenta->numero_cuenta }}
                                </option>
                            @endforeach
                        </select>
                    </div>       
                    <div class="mb-3">
                        <label for="movementType" class="form-label">Tipo Movimiento</label>
                        <select id="movementType" name="tipo_movimiento" class="form-select" required>
                            <option value="" disabled selected>Seleccionar tipo</option>
                            <option value="0">Ingreso +</option>
                            <option value="1">Egreso -</option>
                        </select>
                    </div>                   

                    <div class="mb-3">
                        <label for="movementDate" class="form-label">Fecha Movimiento</label>
                        <input type="date" class="form-control" id="movementDate" name="fecha" required>
                    </div>

                    <div class="mb-3">
                        <label for="movementReferencia" class="form-label">Referencia</label>
                        <input type="text" class="form-control" id="movementReferencia" name="referencia" required>
                    </div>

                    <div class="mb-3">
                        <label for="movementMonto" class="form-label">Monto</label>
                        <input type="text" class="form-control" id="movementMonto" name="monto" required>
                    </div>

                    <div class="mb-3">
                        <label for="movementNotas" class="form-label">Notas</label>
                        <input type="text" class="form-control" id="movementNotas" name="notas">
                    </div>

                    <button type="submit" class="btn btn-primary">Agregar Movimiento</button>
                </form>
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
