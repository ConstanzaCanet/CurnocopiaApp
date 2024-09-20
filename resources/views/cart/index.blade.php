
@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Tu Carrito de Compras</h1>

    @if(Cart::count() > 0)
    <div class="row">
        <!-- Carrito de productos -->
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(Cart::content() as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>${{ $item->price }}</td>

                        <!-- Formulario para actualizar la cantidad -->
                        <td>
                            <input type="number" name="quantity" value="{{ $item->qty }}" min="1" class="form-control" style="width: 70px;" onchange="updateCart('{{ $item->rowId }}', this.value)">
                        </td>

                        <td>${{ $item->subtotal }}</td>

                        <!-- Botón para eliminar el producto del carrito -->
                        <td>
                            <form action="{{ route('cart.destroy', $item->rowId) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Resumen del carrito -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4>Total del Pedido</h4>
                    <p>Total: ${{ Cart::total() }}</p>

                    <a href="{{ route('cart.checkout') }}" class="btn btn-success">Proceder al Pago</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary mb-2">Continuar comprando</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <p>No tienes productos en el carrito.</p>
    @endif
</div>

    <script>
        function updateCart(rowId, quantity) {
            fetch(`/cart/update/${rowId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                }
            });
        }
    </script>

@stop