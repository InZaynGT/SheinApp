<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$orden->cliente->nombre}}</title>
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
        .total-row {
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reporte de Orden</h2>
        </div>

        <div class="details">
            <div>
                <p><strong>Cliente:</strong> {{ $orden->cliente->nombre }}</p>
                <p><strong>Fecha Orden:</strong> {{ \Carbon\Carbon::parse($orden->fechaPromesa)->format('d F Y') }}</p>
            </div>
            <div>
                <p><strong>Orden #:</strong> {{ $orden->id }}</p>
            </div>
        </div>

        <h4>Detalles de la Orden</h4>
        <table class="table table-striped table-bordered">
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
                        <td>Q.{{ number_format($detalle->PrecioOfrecido, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total:</td>
                    <td>Q.{{ number_format($orden->detalleOrden->sum('PrecioOfrecido'), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Gracias por su preferencia.</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
