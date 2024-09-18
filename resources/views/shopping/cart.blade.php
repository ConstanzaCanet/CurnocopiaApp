@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Place an Order</h2>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <!-- Dirección de envío -->
        <div class="form-group">
            <label for="shipping_address">Shipping Address</label>
            <textarea name="shipping_address" class="form-control" required></textarea>
        </div>

        <!-- Productos en el carrito -->
        <div class="form-group">
            <label>Products</label>
            <div id="products-list">
                @foreach ($cartProducts as $product)
                <div class="product-item">
                    <p>{{ $product->name }} - ${{ $product->price }}</p>
                    <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                    <input type="number" name="products[{{ $loop->index }}][quantity]" value="1" min="1" required>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Precio total -->
        <div class="form-group">
            <label for="total_price">Total Price</label>
            <input type="number" name="total_price" value="{{ $cartTotal }}" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>
@endsection
