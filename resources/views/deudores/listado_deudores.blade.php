@extends('adminlte::page')

@section('title', 'Listado de Deudores')

@section('content_header')
    <h1 class="m-0 text-dark">Clientes Pendientes de Pago</h1>
@stop

@section('css')
    <style>
        /* Rotación del indicador del accordion al abrir/cerrar */
        .accordion .card-header .accordion-chevron {
            transition: transform .2s ease-in-out;
        }

        .accordion .card-header button:not(.collapsed) .accordion-chevron {
            transform: rotate(180deg);
        }
    </style>
@stop

@section('content')
<div class="container-fluid">
    <table class="table table-striped" id="clientsTable">
        <thead>
            <tr>
                <th>Nombre del Cliente</th>
                <th>Saldo pendiente</th>
                <th style="min-width: 280px;">Documentos pendientes (ID Orden / Fecha / Monto)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deudores as $order)
            <tr>
                <td>{{ $order->cliente->nombre }}</td>
                <td>Q. {{ number_format($order->saldo_total,2)}}</td>
                <td>
                    @include('deudores.partials.documentos_pendientes_accordion', [
                        'documentos' => $order->documentos,
                        'clienteId'  => $order->idCliente,
                    ])
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $deudores->links('vendor.pagination.bootstrap-4') }}
    </div>

</div>

<!-- Scripts comunes centralizados -->

@include('partials.scripts')
@stop


