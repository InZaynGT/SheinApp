@extends('adminlte::page')

@section('title', 'Listado de Clientes')

@section('content_header')
<h1 class="m-0 text-dark">Clientes</h1>
@stop

@section('content')
<div class="container-fluid px-2 px-md-3">
    <!-- Área de Filtros -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clientes.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label for="nombre" class="mr-2">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ $nombre ?? '' }}" placeholder="Buscar por nombre" autocomplete="off">
                </div>
                <div class="form-group mr-2">
                    <label for="tipo_cliente" class="mr-2">Tipo de Cliente:</label>
                    <select name="tipo_cliente" id="tipo_cliente" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ ($tipoCliente ?? '') == '1' ? 'selected' : '' }}>Hombre</option>
                        <option value="2" {{ ($tipoCliente ?? '') == '2' ? 'selected' : '' }}>Mujer</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i> Reestablecer filtros
                </a>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-md-3 mb-2 mb-md-0">
            <button type="button" class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </button>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm" id="clientsTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->direccion }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal"
                            data-id="{{ $cliente->id }}"
                            data-nombre="{{ $cliente->nombre }}"
                            data-direccion="{{ $cliente->direccion }}"
                            data-telefono="{{ $cliente->telefono }}"
                            data-type="{{ $cliente->tipo_cli }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
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
                    <input type="hidden" name="id" id="editClientId">

                    <div class="mb-3">
                        <label for="editClientName" class="form-label">
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="editClientName" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="editClientAddress" class="form-label">
                            <i class="fas fa-map-pin"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="editClientAddress" name="direccion">
                    </div>

                    <div class="mb-3">
                        <label for="editClientPhone" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="editClientPhone" name="telefono">
                    </div>

                    <div class="mb-3">
                        <label for="editClientType" class="form-label">
                            <i class="fas fa-tag"></i> Tipo de Cliente
                        </label>
                        <select id="editClientType" name="tipo_cliente" class="form-select" required>
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
                        <label for="addClientName" class="form-label">
                            <i class="fas fa-user"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="addClientName" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="addClientType" class="form-label">
                            <i class="fas fa-tag"></i> Tipo de Cliente
                        </label>
                        <select id="addClientType" name="tipo_cliente" class="form-select" required>
                            <option value="" disabled selected>Seleccionar tipo</option>
                            <option value="0">Entidad</option>
                            <option value="1">Hombre</option>
                            <option value="2">Mujer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addClientAddress" class="form-label">
                            <i class="fas fa-map-pin"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="addClientAddress" name="direccion" required>
                    </div>

                    <div class="mb-3">
                        <label for="addClientPhone" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="addClientPhone" name="telefono" required>
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

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal de edición
        var editModal = document.getElementById('editClientModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var clientId = button.getAttribute('data-id');
                var clientName = button.getAttribute('data-nombre');
                var clientAddress = button.getAttribute('data-direccion');
                var clientPhone = button.getAttribute('data-telefono');
                var clientType = button.getAttribute('data-type');

                document.getElementById('editClientId').value = clientId || '';
                document.getElementById('editClientName').value = clientName || '';
                document.getElementById('editClientAddress').value = clientAddress || '';
                document.getElementById('editClientPhone').value = clientPhone || '';
                document.getElementById('editClientType').value = clientType || '';
            });
        }

        // Cerrar modales con tecla ESC (Bootstrap 5 ya lo hace, pero por si acaso)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modals = document.querySelectorAll('.modal.show');
                modals.forEach(function(modal) {
                    var bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }
                });
            }
        });
    });
</script>
@endpush
@stop