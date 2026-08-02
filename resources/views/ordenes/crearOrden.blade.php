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
                <label for="cliente_nombre" class="form-label">Cliente</label>
                <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre"
                    placeholder="Empieza a escribir el nombre del cliente" autocomplete="off" required>
                <input type="hidden" name="cliente_id" id="cliente_id">
                <div id="cliente-suggestions" class="list-group"
                    style="position:absolute; z-index:1000; display:none; max-width: 500px;"></div>
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
    </div>

    <!-- Scripts comunes centralizados -->
    @include('partials.scripts')

    <script>

        $(document).ready(function() {
            var searchUrl = "{{ route('buscar.cliente') }}";
            var suggestions = $('#cliente-suggestions');
            var nombreInput = $('#cliente_nombre');
            var idInput = $('#cliente_id');

            // Autocompletado de cliente a partir del 3er caracter
            nombreInput.on('input', function() {
                var term = $(this).val().trim();
                if (term.length < 3) {
                    suggestions.hide().empty();
                    idInput.val('');
                    return;
                }
                $.ajax({
                    url: searchUrl,
                    method: 'GET',
                    data: { term: term },
                    dataType: 'json',
                    success: function(data) {
                        suggestions.empty();
                        if (data.length === 0) {
                            suggestions.hide();
                            return;
                        }
                        $.each(data, function(index, cliente) {
                            var item = $('<a href="#" class="list-group-item list-group-item-action"></a>')
                                .text(cliente.nombre)
                                .on('click', function(e) {
                                    e.preventDefault();
                                    nombreInput.val(cliente.nombre);
                                    idInput.val(cliente.id);
                                    suggestions.hide().empty();
                                });
                            suggestions.append(item);
                        });
                        suggestions.show();
                    }
                });
            });

            // Ocultar sugerencias al hacer clic fuera
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#cliente_nombre, #cliente-suggestions').length) {
                    suggestions.hide();
                }
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
                            $('#cliente_nombre').val('');
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
