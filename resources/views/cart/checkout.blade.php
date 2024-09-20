@extends('adminlte::page')

@section('content')
    <div class="container">
        <h3>Resumen de la Compra</h3>
        <ul>
            @foreach ($cartItems as $item)
                <li>{{ $item->name }} - Cantidad: {{ $item->qty }} - Precio: ${{ $item->subtotal }}</li>
            @endforeach
        </ul>
        <h4>Total: ${{ $totalPrice }}</h4>

        <h3>Datos del Usuario</h3>
        <ul>
            <li>Nombre: {{ $user->name }}</li>
            <li>Apellido: {{ $user->last_name ?? 'No especificado' }}</li> <!-- Si tienes un campo 'last_name' -->
            <li>Email: {{ $user->email }}</li>
        </ul>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div>
                <h4>Dirección de Envío</h4>
                <input type="text" name="shipping_address" placeholder="Dirección de envío" required>
                <input type="text" name="zip" placeholder="Código postal" required>
            </div>

            <button type="submit" class="btn btn-success">Finalizar Compra</button>
        </form>
    </div>
@stop
