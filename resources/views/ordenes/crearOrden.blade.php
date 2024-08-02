@extends('adminlte::page')

@section('title', 'Crear Orden')

@section('content_header')
    <h1 class="m-0 text-dark">Crear Orden</h1>
@stop

@section('content')
    <div class="container">
        <form id="crearOrdenForm">
            @csrf
            <!-- Cliente -->
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <input type="text" class="form-control" id="cliente_id" name="cliente_id" readonly required>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                    data-bs-target="#buscarClienteModal">
                    Buscar Cliente
                </button>
            </div>
            <!-- Fecha de Orden -->
            <div class="mb-3">
                <label for="fecha_promesa" class="form-label">Fecha de Orden</label>
                <input type="date" class="form-control" id="fecha_promesa" name="fecha_promesa" required>
            </div>
            <!-- Productos -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="producto_sku" class="form-label">SKU</label>
                    <input type="text" class="form-control" id="producto_sku">
                </div>
                <div class="col-md-6">
                    <label for="producto_talla" class="form-label">Talla</label>
                    <input type="text" class="form-control" id="producto_talla">
                </div>
            </div>
            <div class="mb-3">
                <label for="producto_descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="producto_descripcion">
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="producto_costo" class="form-label">Costo-MX</label>
                    <input type="number" class="form-control" id="producto_costo">
                </div>
                <div class="col-md-6">
                    <label for="producto_precio" class="form-label">Precio Ofrecido-GT</label>
                    <input type="number" class="form-control" id="producto_precio">
                </div>
            </div>
            <button type="button" class="btn btn-success" id="agregar_producto">Agregar Producto</button>

            <!-- Tabla de Productos -->
            <table class="table table-striped mt-3" id="productos_table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Talla</th>
                        <th>Descripción</th>
                        <th>CostoMX</th>
                        <th>Precio Ofrecido</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Productos agregados dinámicamente -->
                </tbody>
            </table>
            <!-- Botón para Guardar Orden -->
            <button type="button" class="btn btn-primary mt-3" id="guardar_orden">Guardar Orden</button>
        </form>

        <!-- Modal para Buscar Cliente -->
        <div class="modal fade" id="buscarClienteModal" tabindex="-1" aria-labelledby="buscarClienteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buscarClienteModalLabel">Buscar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->id }}</td>
                                        <td>{{ $cliente->nombre }}</td>
                                        <td>{{ $cliente->direccion }}</td>
                                        <td>{{ $cliente->telefono }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary seleccionar-cliente"
                                                data-id="{{ $cliente->id }}" data-nombre="{{ $cliente->nombre }}">
                                                Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        $(document).ready(function() {
            // Seleccionar cliente
            $('.seleccionar-cliente').on('click', function() {
                var clienteId = $(this).data('id');
                $('#cliente_id').val(clienteId);
                $('#buscarClienteModal').modal('hide');
            });

            // Agregar producto a la tabla
            $('#agregar_producto').on('click', function() {
                var sku = $('#producto_sku').val();
                var talla = $('#producto_talla').val();
                var descripcion = $('#producto_descripcion').val();
                var costo = $('#producto_costo').val();
                var precio = $('#producto_precio').val();

                // Validar campos
                if (sku && talla && descripcion && costo && precio) {
                    $('#productos_table tbody').append(`
                    <tr>
                        <td>${sku}</td>
                        <td>${talla}</td>
                        <td>${descripcion}</td>
                        <td>${costo}</td>
                        <td>${precio}</td>
                    </tr>
                `);

                    // Limpiar campos de entrada
                    $('#producto_sku').val('');
                    $('#producto_talla').val('');
                    $('#producto_descripcion').val('');
                    $('#producto_costo').val('');
                    $('#producto_precio').val('');
                } else {
                    alert('Por favor, completa todos los campos.');
                }
            });

            // Guardar orden
            $('#guardar_orden').on('click', function() {
                var clienteId = $('#cliente_id').val();
                var fechaPromesa = $('#fecha_promesa').val();

                if (!clienteId || !fechaPromesa) {
                    alert('Por favor, selecciona un cliente y proporciona una fecha de promesa.');
                    return;
                }

                var productos = [];
                $('#productos_table tbody tr').each(function() {
                    var row = $(this);
                    var producto = {
                        SKU: row.find('td').eq(0).text(),
                        talla: row.find('td').eq(1).text(),
                        descripcion: row.find('td').eq(2).text(),
                        costo: row.find('td').eq(3).text(),
                        precio: row.find('td').eq(4).text()
                    };
                    productos.push(producto);
                });

                // Enviar datos al servidor
                $.ajax({
                    url: '{{ route('ordenes.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cliente_id: clienteId,
                        fecha_promesa: fechaPromesa,
                        productos: productos
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Orden guardada con éxito.');
                            // Abrir una nueva ventana con el PDF
                            window.open('/impresion_orden/' + response.orden_id, '_blank');
                            // Limpiar campos y tabla después de guardar
                            $('#cliente_id').val('');
                            $('#fecha_promesa').val('');
                            $('#productos_table tbody').empty();
                        } else {
                            alert('Error al guardar la orden: ' + response.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error al guardar la orden:', error);
                        alert('Ocurrió un error al guardar la orden.');
                    }
                });
            });
        });
    </script>
@stop
