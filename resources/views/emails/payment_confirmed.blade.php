<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
</head>
<body>
    <h1>Confirmación de Pago</h1>
    <p>Estimado/a {{ $order->user->name }},</p>
    <p>Tu pago para el pedido #{{ $order->id }} ha sido confirmado exitosamente.</p>
    <p>Detalles del pedido:</p>
    <ul>
        <li>Total: ${{ $order->total_price }}</li>
        <li>Estado del Pedido: {{ $order->status }}</li>
    </ul>
    <p>Gracias por tu compra.</p>
</body>
</html>