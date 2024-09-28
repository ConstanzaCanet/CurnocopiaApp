<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación</title>
</head>
<body>
    <h1>Has agregato un producto!</h1>
    <p>Estimado/a {{ $product->user->name }},</p>
    <p>Has agregado {{ $product->name }} para la venta</p>
    <p>Detalles del producto:</p>
    <ul>
        <li>Nombre: ${{ $product->name }}</li>
        <li>Precio: ${{ $product->price }}</li>
        <li>Imagen: {{ $product->image }}</li>
    </ul>
    <p>Gracias por elegirnos.</p>
</body>
</html>