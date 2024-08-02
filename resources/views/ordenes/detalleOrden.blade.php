@extends('adminlte::page')

@section('title', 'Listado de Órdenes')

@section('content_header')
    <h1 class="m-0 text-dark">Listado de Órdenes</h1>
@stop

@section('content')
    <div class="container">
        <h3>Orden #{{ $orden->id }}</h3>
        <p><strong>Cliente:</strong> {{ $orden->cliente->nombre }}</p>
        <p><strong>Fecha Promesa:</strong> {{ \Carbon\Carbon::parse($orden->fechaPromesa)->format('d F Y') }}</p>

        <h4>Detalles de la Orden</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Talla</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->detalleOrden as $detalle)
                    <tr>
                        <td>{{ $detalle->SKU }}</td>
                        <td>{{ $detalle->talla }}</td>
                        <td>{{ $detalle->descripcion }}</td>
                        <td>Q. {{ number_format($detalle->PrecioOfrecido, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
