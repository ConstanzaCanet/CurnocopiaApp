
@extends('adminlte::page')

@section('content')
    <div class="container">
        <h1>Tu Carrito de Compras</h1>

        @if(Cart::count() > 0)
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
                                <form action="{{ route('cart.update', $item->rowId) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->qty }}" min="1" class="form-control" style="width: 70px;">
                                    <button type="submit" class="btn btn-primary mt-1">Actualizar</button>
                                </form>
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

            <div class="mt-3">
                <h4>Total: ${{ Cart::total() }}</h4>
                <a href="{{ route('dashboard') }}" class="btn btn-success">Proceder al Pago</a>
            </div>

        @else
            <p>No tienes productos en el carrito.</p>
        @endif
    </div>

@stop