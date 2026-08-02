@extends('adminlte::page')

@section('title', 'Listado de Órdenes')

@section('content_header')
<h1 class="m-0 text-dark">Listado de Órdenes</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Área de Filtros -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ordenes.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label for="nombre_cliente" class="mr-2">Nombre Cliente:</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control"
                        value="{{ $nombreCliente ?? '' }}" placeholder="Empieza a escribir" autocomplete="off">
                    <input type="hidden" name="id_cliente" id="id_cliente" value="{{ $idCliente ?? '' }}">
                    <div id="cliente-suggestions" class="list-group" style="position:absolute; z-index:1000; display:none;"></div>
                </div>

                <div class="form-group mr-2">
                    <label for="fecha_inicio" class="mr-2">Fecha Inicial:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="{{ $fechaInicio ?? '' }}">
                </div>
                <div class="form-group mr-2">
                    <label for="fecha_fin" class="mr-2">Fecha Final:</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                        value="{{ $fechaFin ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="{{ route('ordenes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i> Limpiar
                </a>
            </form>
        </div>
    </div>

    <!-- Tabla de Órdenes -->
    <table class="table table-striped">

        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Orden</th>
                <th>Nombre del Cliente</th>
                <th>Monto de la Orden</th>
                <th>Saldo Pendiente</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="ordenes-table">
            @foreach($ordenes as $order)
            <tr>
                <td style="width: 100px; padding: 10px;">{{ $order->id }}</td>
                <td>{{ \Carbon\Carbon::parse($order->fechaPromesa)->format('d F Y') }}</td>
                <td>{{ $order->cliente->nombre }}</td>
                <td>Q. {{ number_format($order->CXC->montoDocto,2) }}</td>
                <td>Q. {{ number_format($order->CXC->saldoDocto,2) }}</td>
                <td>
                    @switch($order->estado)
                    @case(0)
                    <span class="badge badge-warning">Solicitado</span>
                    @break
                    @case(1)
                    <span class="badge badge-danger">Cancelado</span>
                    @break
                    @case(2)
                    <span class="badge badge-success">Confirmado</span>
                    @break
                    @case(3)
                    <span class="badge badge-primary">En Bodega</span>
                    @break
                    @case(4)
                    <span class="badge badge-orange" style="background-color: orange;">Parcialmente pagado</span>
                    @break
                    @case(5)
                    <i class="fas fa-check text-success"></i>
                    @break
                    @endswitch
                </td>
                <td>
                    <a href="{{ route('ordenes.show', $order->id) }}" class="btn btn-info" target="_blank"><i class="fas fa-list"></i></a>
                    <a href="{{ route('voting-result', $order->id) }}" class="btn btn-warning" target="_blank"><i class="fas fa-print"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $ordenes->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        var searchUrl = "{{ route('buscar.cliente') }}";
        var suggestions = $('#cliente-suggestions');
        var nombreInput = $('#nombre_cliente');
        var idInput = $('#id_cliente');

        nombreInput.on('input', function () {
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
                success: function (data) {
                    suggestions.empty();
                    if (data.length === 0) {
                        suggestions.hide();
                        return;
                    }
                    $.each(data, function (index, cliente) {
                        var item = $('<a href="#" class="list-group-item list-group-item-action"></a>')
                            .text(cliente.nombre)
                            .on('click', function (e) {
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

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#nombre_cliente, #cliente-suggestions').length) {
                suggestions.hide();
            }
        });
    });
</script>
@stop


