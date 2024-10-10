@extends('adminlte::page')

@section('title', 'Wishlist')
@section('content')
<div class="container text-center">
    <h1 class="pt-4">Mi Lista de Deseos</h1>
    <hr class="my-4">
    @if ($wishlistItems->isEmpty())
        <p>No tienes productos en tu wishlist.</p>
    @else
        <div class="row">
            @foreach ($wishlistItems as $item)
                <div class="col-6">
                    <div class="card">
                        <img src="{{ $item->product->images->first()->path }}" class="card-img-top" alt="{{ $item->product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->product->name }}</h5>
                            <p class="card-text">{{ $item->product->description }}</p>
                            <a href="{{ route('products.show', $item->product->id) }}" class="btn btn-primary">Ver Producto</a>
                            @if($item->product->user_id !== auth()->id())
                            <form action="{{ route('cart.add', $item->product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Agregar al carrito
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
