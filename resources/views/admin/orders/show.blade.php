@extends('adminlte::page')
@section('content')
<div class="container">
    <h3 class="pt-3">Detalles de la compra</h3>
    <p><strong>ID de la orden:</strong> {{ $order->id }}</p>
    <p><strong>Total:</strong> ${{ $order->total_price }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>

    <form action="{{ route('admin.orders.sendInvoice', $order) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary">Enviar Factura</button>
    </form>    
    
    @include('admin.users.sendMessage', ['user' => $order->user])

    <a href="{{ route('admin.users.show',$user) }}" class="btn btn-secondary">Volver</a>
</div>
@endsection