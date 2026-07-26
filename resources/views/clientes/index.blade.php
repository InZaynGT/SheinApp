@extends('adminlte::page')

@section('title', 'Listado de Clientes')

@section('content_header')
    <h1 class="m-0 text-dark">Clientes</h1>
@stop

@section('content')
<div class="container-fluid px-2 px-md-3">
    <div class="row mb-3">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <button type="button" class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </button>
        </div>
        <div class="col-12 col-md-6">
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="customSearch" placeholder="Buscar cliente...">
                <button class="btn btn-outline-secondary" id="clearSearch" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm" id="clientsTable">
            <thead class="table-light">
                <tr>
                    <th class="d-none d-md-table-cell">ID</th>
                    <th>Cliente</th>
                    <th class="d-none d-lg-table-cell">Dirección</th>
                    <th class="d-none d-sm-table-cell">Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $order)
                <tr>
                    <td class="d-none d-md-table-cell">{{ $order->id }}</td>
                    <td>
                        <strong>{{ $order->nombre }}</strong>
                        <div class="d-md-none small text-muted">
                            @if($order->direccion)
                                <i class="fas fa-map-pin"></i> {{ Str::limit($order->direccion, 30) }}<br>
                            @endif
                            @if($order->telefono)
                                <i class="fas fa-phone"></i> {{ $order->telefono }}
                            @endif
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">{{ $order->direccion }}</td>
                    <td class="d-none d-sm-table-cell">{{ $order->telefono }}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editClientModal"
                                data-id="{{ $order->id }}"
                                data-nombre="{{ $order->nombre }}"
                                data-direccion="{{ $order->direccion }}"
                                data-telefono="{{ $order->telefono }}"
                                data-type="{{ $order->tipo_cli }}">
                                <i class="fas fa-edit"></i>
                                <span class="d-none d-sm-inline">Editar</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Editar Cliente -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">
                    <i class="fas fa-user-edit"></i> Editar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm" method="POST" action="{{ route('cliente.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="clientId">

                    <div class="mb-3">
                        <label for="clientName" class="form-label">
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="clientName" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="clientAddress" class="form-label">
                            <i class="fas fa-map-pin"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="clientAddress" name="direccion">
                    </div>

                    <div class="mb-3">
                        <label for="clientPhone" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="clientPhone" name="telefono">
                    </div>

                    <div class="mb-3">
                        <label for="clientType" class="form-label">
                            <i class="fas fa-tag"></i> Tipo de Cliente
                        </label>
                        <select id="clientType" name="tipo_cliente" class="form-select" required>
                            <option value="" disabled>Seleccionar tipo</option>
                            <option value="0">Entidad</option>
                            <option value="1">Hombre</option>
                            <option value="2">Mujer</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Cliente -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">
                    <i class="fas fa-user-plus"></i> Agregar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm" method="POST" action="{{ route('cliente.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="newClientName" class="form-label">
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="newClientName" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="newClientType" class="form-label">
                            <i class="fas fa-tag"></i> Tipo de Cliente
                        </label>
                        <select id="newClientType" name="tipo_cliente" class="form-select" required>
                            <option value="" disabled selected>Seleccionar tipo</option>
                            <option value="0">Entidad</option>
                            <option value="1">Hombre</option>
                            <option value="2">Mujer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="newClientAddress" class="form-label">
                            <i class="fas fa-map-pin"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="newClientAddress" name="direccion" required>
                    </div>

                    <div class="mb-3">
                        <label for="newClientPhone" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="newClientPhone" name="telefono" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus"></i> Agregar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de DataTables con configuración mobile-friendly -->
@push('js')
<script>
    $(document).ready(function() {
        // Inicializar DataTable con configuración responsive
        var table = $('#clientsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                "infoEmpty": "No hay clientes disponibles",
                "zeroRecords": "No se encontraron clientes"
            },
            "processing": true,
            "serverSide": false,
            "order": [[0, 'desc']],
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                {
                    "targets": [5],
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": [0],
                    "visible": true,
                    "responsivePriority": 3
                },
                {
                    "targets": [1],
                    "responsivePriority": 1
                },
                {
                    "targets": [2],
                    "visible": true,
                    "responsivePriority": 4
                },
                {
                    "targets": [3],
                    "visible": true,
                    "responsivePriority": 2
                },
                {
                    "targets": [4],
                    "visible": true,
                    "responsivePriority": 5
                },
                {
                    "targets": [5],
                    "responsivePriority": 6
                }
            ],
            "dom": '<"row"<"col-12"t>>' +
                   '<"row"<"col-12 col-md-5"i><"col-12 col-md-7"p>>',
            "drawCallback": function() {
                // Asegurar que la tabla sea responsive después de cada dibujado
                $('.dataTables_wrapper').addClass('table-responsive');
            }
        });

        // Buscador personalizado (ocultar el buscador de DataTables)
        $('#clientsTable_filter').hide();
        
        // Vincular el buscador personalizado
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Limpiar buscador
        $('#clearSearch').on('click', function() {
            $('#customSearch').val('');
            table.search('').draw();
        });

        // Modal de edición
        var editClientModal = document.getElementById('editClientModal');
        editClientModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var clientId = button.getAttribute('data-id');
            var clientName = button.getAttribute('data-nombre');
            var clientAddress = button.getAttribute('data-direccion');
            var clientPhone = button.getAttribute('data-telefono');
            var clientType = button.getAttribute('data-type');

            document.getElementById('clientId').value = clientId;
            document.getElementById('clientName').value = clientName;
            document.getElementById('clientAddress').value = clientAddress;
            document.getElementById('clientPhone').value = clientPhone;
            document.getElementById('clientType').value = clientType;
        });

        // Cerrar modales con tecla ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.modal').modal('hide');
            }
        });

        // Touch-friendly para dispositivos móviles
        if ('ontouchstart' in window) {
            $('.btn-group .btn').on('touchstart', function() {
                $(this).addClass('active');
            }).on('touchend', function() {
                $(this).removeClass('active');
            });
        }
    });
</script>
@endpush
@stop