<h1>Carrito de Compras</h1>

@if (Cart::count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total: {{ Cart::total() }}</p>
@else
    <p>Tu carrito está vacío</p>
@endif

