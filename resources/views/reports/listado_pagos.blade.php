<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Pago</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            color: #333;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 750px;
            margin: auto;
        }
        .header, .footer {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .footer {
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 20px;
            font-size: 12px;
        }
        .table {
            width: 100%;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            font-size: 14px;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .table tbody tr:hover {
            background-color: #ddd;
        }
        .details, .order-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .details {
            display: flex;
            justify-content: space-between;
        }
        .details p, .order-info p {
            margin: 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        @foreach($listado_pagos as $pago)
            <div class="header">
                <h2>Recibo de Pago</h2>
            </div>
            <div class="details">
                <div>
                    <p><strong>Cliente:</strong> {{ $pago->Clientes->nombre }}</p>
                    <p><strong>Dirección:</strong> {{ $pago->Clientes->direccion }}</p>
                    <p><strong>Teléfono:</strong> {{ $pago->Clientes->telefono }}</p>
                </div>
                <div>
                    <p><strong>Fecha de Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha)->format('d F Y') }}</p>
                </div>
            </div>

            <h4>Detalles del Pago</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>Referencia</th>
                        <th>Monto</th>
                        <th>Fecha de Creación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>{{ $pago->referencia }}</td>
                        <td>Q.{{ number_format($pago->monto, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('d F Y') }}</td>
                    </tr>
                </tbody>
            </table>

            @if($pago->PagosDet)
            <div class="col-md-6 mx-auto">
                <h4>Órdenes Relacionadas</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>Monto Aplicado</th>
                            <th>Fecha Orden</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pago->PagosDet as $pagoDet)
                            @if($pagoDet->orden)
                            <tr>
                                <td>{{ $pagoDet->orden->id }}</td>
                                <td>Q.{{ number_format($pagoDet->monto_aplicado,2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($pagoDet->orden->fechaPromesa)->format('d F Y') }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @endforeach

        <div class="footer">
            <p>Gracias por su preferencia.</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
