@extends('adminlte::page')

@section('title', 'Listado de Deudores')

@section('content_header')
    <h1 class="m-0 text-dark">Clientes Pendientes de Pago</h1>
@stop

@section('content')
<div class="container-fluid">
    <table class="table table-striped" id="clientsTable">
        <thead>
            <tr>
                <th>Nombre del Cliente</th>
                <th>Saldo pendiente</th>
                <th>Documentos pendientes (ID Orden / Fecha / Monto)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deudores as $order)
            <tr>
                <td>{{ $order->cliente->nombre }}</td>
                <td>Q. {{ number_format($order->saldo_total,2)}}</td>
                <td>
                    @if($order->documentos->count() > 0)
                        @foreach($order->documentos as $doc)
                            <span class="text-danger">
                                #{{ $doc->Nro_docto }} - {{ \Carbon\Carbon::parse($doc->fechaDocto)->format('d/m/Y') }} - Q. {{ number_format($doc->saldoDocto,2) }}
                            </span><br>
                        @endforeach
                    @else
                        <span class="text-muted">Sin documentos pendientes</span>
                    @endif
                </td>
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
