@extends('adminlte::page')

@section('title', 'Deudores')

@section('content_header')
    <h1 class="m-0 text-dark">Cuentas por Cobrar</h1>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Buscar Cliente -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="cliente">Buscar Cliente:</label>
                    <select class="form-control" id="cliente">
                        <option value=" ">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Documentos Section -->
        <div class="row">
            <div class="col-md-12">
                <h3>Órdenes Pendientes de Pago</h3>
                <table class="table table-striped" id="documentos-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>No. de Orden</th>
                            <th>Fecha de la Orden</th>
                            <th>Monto total de la Orden</th>
                            <th>Pagos aplicados</th>
                            <th>Total de Pagos Acumulados</th>
                            <th>Saldo pendiente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Documentos will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagos Aplicados Section -->
        <div class="row">
            <div class="col-md-12">
                <h3>Pagos Aplicados</h3>
                <table class="table table-striped" id="pagos-aplicados-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha de Pago</th>
                            <th>Forma de Pago</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Pagos aplicados will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ingresar Pago Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ingresar pago</h3>
                    </div>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                        data-target="#modalAnticipo">
                        Ingresar Anticipo
                    </button>
                    <div class="panel-body">
                        <form id="form-pago">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="forma_pago">Forma de pago:</label>
                                        <select id="forma_pago" name="forma_pago" class="form-control">
                                            @foreach ($formasPago as $pago)
                                                <option value="{{ $pago->id }}">{{ $pago->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label id="anticipo_label" style="display:none">5</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fecha_contabilizacion">Fecha de contabilización:</label>
                                        <input type="date" id="fecha_contabilizacion" name="fecha_contabilizacion"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="referencia">Referencia:</label>
                                        <input type="text" id="referencia" name="referencia" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6" id="div_documento" style="display:none">
                                    <div class="form-group">
                                        <label for="numero_documento">Número de documento:</label>
                                        <input type="text" id="numero_documento" name="numero_documento"
                                            class="form-control" style="display:none;">
                                    </div>
                                </div>
                                <div class="col-md-6" id="div_cuenta_bancaria" style="display:none">
                                    <div class="form-group">
                                        <label for="cuenta_bancaria">Cuenta Bancaria:</label>
                                        <select id="cuenta_bancaria" name="cuenta_bancaria" class="form-control"
                                            style="display:none;">
                                            <option value="0">Seleccione un cliente</option>
                                            @foreach ($cuentasBancarias as $pago)
                                                <option value="{{ $pago->id }}">{{ $pago->Bancos->nombre }} -
                                                    {{ $pago->nombre_cuenta }} - {{ $pago->numero_cuenta }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="saldo_total" class="col-md-4 col-form-label text-left">Saldo
                                            total:</label>
                                        <div class="col-md-8">
                                            <input type="text" id="saldo_total" name="saldo_total"
                                                class="form-control text-right" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="monto_seleccionado" class="col-md-4 col-form-label text-left">Monto
                                            seleccionado:</label>
                                        <div class="col-md-8">
                                            <input type="text" id="monto_seleccionado" name="monto_seleccionado"
                                                class="form-control text-right" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="monto_a_operar" class="col-md-4 col-form-label text-left">Monto a
                                            operar:</label>
                                        <div class="col-md-8">
                                            <input type="text" id="monto_a_operar" name="monto_a_operar"
                                                class="form-control text-right">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar Pago</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Ingresar Anticipo -->
    <div class="modal fade" id="modalAnticipo" tabindex="-1" role="dialog" aria-labelledby="modalAnticipoLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnticipoLabel">Ingresar Anticipo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-anticipo">
                        <div class="form-group">
                            <label for="anticipo_cliente">Cliente:</label>
                            <select id="anticipo_cliente" name="anticipo_cliente" class="form-control">
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="anticipo_forma_pago">Forma de Pago:</label>
                            <select id="anticipo_forma_pago" name="anticipo_forma_pago" class="form-control">
                                @foreach ($formasPago as $pago)
                                    <option value="{{ $pago->id }}">{{ $pago->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="anticipo_fecha">Fecha de Anticipo:</label>
                            <input type="date" id="anticipo_fecha" name="anticipo_fecha" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="anticipo_monto">Monto:</label>
                            <input type="text" id="anticipo_monto" name="anticipo_monto" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="anticipo_observaciones">Observaciones:</label>
                            <textarea id="anticipo_observaciones" name="anticipo_observaciones" class="form-control"></textarea>
                        </div>
                        <div class="form-group" style="display:none;" id="div_anticipo_referencia">
                            <label for="anticipo_referencia">Nro. de Referencia:</label>
                            <textarea id="anticipo_referencia" name="anticipo_referencia" class="form-control"></textarea>
                        </div>
                        <div class="form-group" style="display:none;" id="div_anticipo_cuenta_bancaria">
                            <label for="anticipo_cuenta_bancaria">Cuenta Bancaria:</label>
                            <select id="anticipo_cuenta_bancaria" name="anticipo_cuenta_bancaria" class="form-control">
                                <option value="0">Seleccione un cliente</option>
                                @foreach ($cuentasBancarias as $pago)
                                    <option value="{{ $pago->id }}">{{ $pago->Bancos->nombre }} -
                                        {{ $pago->nombre_cuenta }} - {{ $pago->numero_cuenta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Anticipo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("cliente").value = ' ';
            document.getElementById("forma_pago").value = '2';
            document.getElementById("cuenta_bancaria").value = '0';
            document.getElementById("fecha_contabilizacion").value = ' ';
            var inputSaldoTotal = document.getElementById("monto_a_operar");
            var inputSaldoAOperar = document.getElementById("saldo_total");
            var inputReferencia = document.getElementById("referencia");
            var inputNumeroDoc = document.getElementById("numero_documento");
            inputNumeroDoc.value = "";
            inputReferencia.value = "";
            inputSaldoAOperar.value = 0;
            inputSaldoTotal.value = 0;

            let documentosSeleccionados = [];
            let saldoTotal = 0;

            document.getElementById('cliente').addEventListener('change', function() {
                var clienteId = this.value;

                // Limpia la tabla de documentos
                var documentosTable = document.getElementById('documentos-table').querySelector('tbody');
                documentosTable.innerHTML = '';

                documentosSeleccionados = [];
                saldoTotal = 0;
                document.getElementById('saldo_total').value = '';
                document.getElementById('monto_seleccionado').value = '';

                if (clienteId) {
                    fetch('/deudores/anticipos/' + clienteId)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        document.getElementById('anticipo_label').innerHTML = "Disponible: Q." + data.toFixed(2);
                    })
                    fetch('/deudores/documentos/' + clienteId)
                        .then(response => response.json())
                        .then(data => {
                            saldoTotal = data.reduce((acc, doc) => acc + parseFloat(doc.saldoDocto), 0);
                            document.getElementById('saldo_total').value = saldoTotal.toFixed(2);

                            data.forEach(documento => {
                                var row = document.createElement('tr');
                                var montoDocto = parseFloat(documento.montoDocto).toFixed(2);
                                var saldoDocto = parseFloat(documento.saldoDocto).toFixed(2);
                                var acumuladoPagos = parseFloat(documento.totalAcumuladoPagos)
                                    .toFixed(2);
                                row.classList.add('document-row');
                                row.dataset.id = documento.id;
                                row.dataset.saldo = saldoDocto;
                                row.dataset.fecha = documento.fechaDocto;
                                row.innerHTML = `
                            <td><input type="checkbox" class="document-checkbox"></td>
                            <td>${documento.Nro_docto}</td>
                            <td>${documento.fechaDocto}</td>
                            <td>Q. ${montoDocto}</td>
                            <td>${documento.nroPagos}</td>
                            <td>Q. ${acumuladoPagos}</td>
                            <td><b>Q. ${saldoDocto}</b></td>
                        `;
                                documentosTable.appendChild(row);

                                // Añadir evento de clic a cada fila de documento para cargar los pagos aplicados
                                row.addEventListener('click', function() {
                                    var documentoId = this.dataset.id;
                                    fetch(`/deudores/pagos-aplicados/${documentoId}`)
                                        .then(response => response.json())
                                        .then(pagos => {
                                            var pagosAplicadosTable = document
                                                .getElementById(
                                                    'pagos-aplicados-table')
                                                .querySelector('tbody');
                                            pagosAplicadosTable.innerHTML = '';
                                            pagos.forEach(pago => {
                                                var row = document
                                                    .createElement('tr');
                                                row.innerHTML = `
                                            <td>${pago.id}</td>
                                            <td>${pago.pago_enc.fecha}</td>
                                            <td>${pago.pago_enc.forma_pago.nombre}</td>
                                            <td>${pago.pago_enc.referencia}</td>
                                            <td>Q. ${parseFloat(pago.monto_aplicado).toFixed(2)}</td>
                                        `;
                                                pagosAplicadosTable
                                                    .appendChild(row);
                                            });
                                        });
                                });
                            });

                            document.querySelectorAll('.document-checkbox').forEach(function(checkbox) {
                                checkbox.addEventListener('change', function() {
                                    var row = this.closest('tr');
                                    var saldo = parseFloat(row.dataset.saldo);
                                    var documentoId = row.dataset.id;
                                    var fechaDocto = row.dataset.fecha;

                                    if (this.checked) {
                                        documentosSeleccionados.push({
                                            id: documentoId,
                                            saldo: saldo,
                                            fechaDocto: fechaDocto
                                        });
                                    } else {
                                        documentosSeleccionados =
                                            documentosSeleccionados.filter(doc => doc
                                                .id !== documentoId);
                                    }

                                    var montoSeleccionado = documentosSeleccionados
                                        .reduce((acc, doc) => acc + doc.saldo, 0);
                                    document.getElementById('monto_seleccionado')
                                        .value = montoSeleccionado.toFixed(2);

                                    // Ordenar documentos seleccionados por fecha en orden ascendente
                                    documentosSeleccionados.sort(function(a, b) {
                                        return new Date(a.fechaDocto) -
                                            new Date(b.fechaDocto);
                                    });
                                });
                            });
                        });
                }
            });

            document.getElementById('forma_pago').addEventListener('change', function() {
                var selectedValue = this.value;
                document.getElementById('numero_documento').style.display = 'none';
                document.getElementById('cuenta_bancaria').style.display = 'none';
                document.getElementById('anticipo_label').style.display = 'none';
                document.getElementById('div_cuenta_bancaria').style.display = 'none';
                document.getElementById('div_documento').style.display = 'none';
                document.getElementById('anticipo_label').style.display = 'none';
                if (selectedValue == '3' || selectedValue == '4') {
                    document.getElementById('numero_documento').style.display = 'block';
                    document.getElementById('cuenta_bancaria').style.display = 'block';
                    document.getElementById('div_documento').style.display = 'block';
                    document.getElementById('div_cuenta_bancaria').style.display = 'block';
                }
                else if(selectedValue == '5'){
                    document.getElementById('anticipo_label').style.display = 'block';
                }
            });

            document.getElementById('anticipo_forma_pago').addEventListener('change', function() {
                    var selectedValue = this.value;
                    document.getElementById('div_anticipo_referencia').style.display = 'none';
                    document.getElementById('div_anticipo_cuenta_bancaria').style.display = 'none';
                    if (selectedValue == '3' || selectedValue == '4') {
                        document.getElementById('div_anticipo_referencia').style.display = 'block';
                        document.getElementById('div_anticipo_cuenta_bancaria').style.display = 'block';
                    }
                });

            document.getElementById('form-anticipo').addEventListener('submit', function(event) {
                event.preventDefault();

                var data = {
                    cliente_id: document.getElementById('anticipo_cliente').value,
                    forma_pago: document.getElementById('anticipo_forma_pago').value,
                    fecha: document.getElementById('anticipo_fecha').value,
                    monto: document.getElementById('anticipo_monto').value,
                    observaciones: document.getElementById('anticipo_observaciones').value,
                    nro_documento: document.getElementById('anticipo_referencia').value,
                    cuenta_bancaria: document.getElementById('anticipo_cuenta_bancaria').value
                };

                fetch('{{ route('anticipos.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Anticipo registrado exitosamente');
                            $('#modalAnticipo').modal('hide');
                            window.open('/impresion_pago/' + result.orden_id, '_blank');
                            location.reload();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    });
            });


            document.getElementById('form-pago').addEventListener('submit', function(event) {
                event.preventDefault();

                var data = {
                    cliente_id: document.getElementById('cliente').value,
                    forma_pago: document.getElementById('forma_pago').value,
                    fecha_contabilizacion: document.getElementById('fecha_contabilizacion').value,
                    referencia: document.getElementById('referencia').value,
                    monto_total: parseFloat(document.getElementById('monto_a_operar').value),
                    documentos_seleccionados: documentosSeleccionados,
                    numero_documento: document.getElementById('numero_documento').value,
                    cuenta_bancaria: document.getElementById('cuenta_bancaria').value
                };

                fetch('{{ route('pagos.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Pago registrado exitosamente');
                            window.open('/impresion_pago/' + result.orden_id, '_blank');
                            location.reload();
                        } else {
                            alert('Error: ' + result.error);
                        }
                    });
            });
        });
    </script>

@stop
