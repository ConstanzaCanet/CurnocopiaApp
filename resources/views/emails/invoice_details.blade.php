<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de compra</title>
</head>
<body>
    <h1>Factura de tu compra</h1>
    <p>Gracias por tu compra, {{ $order->user->name }}.</p>
    
    <h3>Detalles de la orden</h3>
    <p><strong>Orden ID:</strong> {{ $order->id }}</p>
    <p><strong>Total:</strong> ${{ $order->total_price }}</p>
    
    <h4>Productos:</h4>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->product->name }} - Cantidad: {{ $item->quantity }} - Precio: ${{ $item->price_at_purchase }}</li>
        @endforeach
    </ul>

    <p>¡Esperamos verte de nuevo!</p>
</body>
</html>
