@extends('adminlte::page')

@section('title', 'Listado de Deudores')

@section('content_header')
    <h1 class="m-0 text-dark">Clientes Pendientes de Pago</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre">
        </div>
    </div> --}}
    <table class="table table-striped" id="clientsTable">
        <thead>
            <tr>
                <th>Nombre del Cliente</th>
                <th>Saldo pendiente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deudores as $order)
            <tr>
                <td>{{ $order->cliente->nombre }}</td>  
                <td>Q. {{ number_format($order->saldo_total,2)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

<script>
</script>
@stop
