@extends('adminlte::page')

@section('content')
 
 <div class="card">
        <h3>{{ $product->name }}</h3>
        @if($product->images->isNotEmpty())
                                @foreach ($product->images as $image)
                                    <img src="{{ $image->path }}" class="product-image">
                                @endforeach
                            @else
                                <p>No hay imágenes disponibles para este producto.</p>
                            @endif
        <p>{{ $product->description }}</p>
        <p>Precio: ${{ $product->price }}</p>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Volver al listado de productos</a>
@stop