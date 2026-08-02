@extends('adminlte::page')

@section('title', 'Listado de Clientes')

@section('content_header')
    <h1 class="m-0 text-dark">Listado de Pagos</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Área de Filtros -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('deudores.consulta_pagos') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label for="nombre" class="mr-2">Nombre Cliente:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ $nombre ?? '' }}" placeholder="Buscar por nombre" autocomplete="off">
                </div>
                <div class="form-group mr-2">
                    <label for="forma_pago" class="mr-2">Forma de Pago:</label>
                    <select name="forma_pago" id="forma_pago" class="form-control">
                        <option value="">Todas</option>
                        @foreach($formasPago as $fp)
                            <option value="{{ $fp->id }}" {{ ($formaPago ?? '') == $fp->id ? 'selected' : '' }}>
                                {{ $fp->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="cuenta_bancaria" class="mr-2">Cuenta Bancaria:</label>
                    <select name="cuenta_bancaria" id="cuenta_bancaria" class="form-control">
                        <option value="">Todas</option>
                        @foreach($cuentasBancarias as $cb)
                            <option value="{{ $cb->id }}" {{ ($cuentaBancaria ?? '') == $cb->id ? 'selected' : '' }}>
                                {{ optional($cb->Bancos)->nombre . ' - ' . $cb->nombre_cuenta . ' - ' . $cb->numero_cuenta }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="nro_documento" class="mr-2">N°. Documento:</label>
                    <input type="text" name="nro_documento" id="nro_documento" class="form-control"
                        value="{{ $nroDocumento ?? '' }}" placeholder="N°. de documento bancario" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="{{ route('deudores.consulta_pagos') }}" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i> Reestablecer filtros
                </a>
            </form>
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

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $listado_pagos->links('vendor.pagination.bootstrap-4') }}
    </div>

</div>


<!-- Scripts comunes centralizados -->

@include('partials.scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {
        var editClientModal = document.getElementById('editClientModal');
        if (editClientModal) {
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
        }
    });
</script>
@stop


