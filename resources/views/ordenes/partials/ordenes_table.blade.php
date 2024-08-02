<!-- resources/views/ordenes/partials/ordenes_table.blade.php -->
<tbody id="ordenes-table">
    @foreach($ordenes as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->cliente->nombre }}</td>
        <td>Q. {{ number_format($order->CXC->montoDocto, 2) }}</td>
        <td>Q. {{ number_format($order->CXC->saldoDocto, 2) }}</td>
        <td>{{ \Carbon\Carbon::parse($order->fechaPromesa)->format('d F Y') }}</td>
        <td>
            <a href="{{ route('ordenes.show', $order->id) }}" class="btn btn-info">Ver Detalle</a>
        </td>
    </tr>
    @endforeach
</tbody>
