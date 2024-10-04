@extends('adminlte::page')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- Carrusel de Imágenes -->
            <div id="productCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($product->images as $key => $image)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            @if (filter_var($image->path, FILTER_VALIDATE_URL))
                                <img src="{{ $image->path }}" class="d-block w-100" alt="Imagen del producto">
                            @else
                                <img src="{{ Storage::url($image->path) }}" class="d-block w-100" alt="Imagen del producto">
                            @endif
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                </a>
            </div>    
            
        </div>

        <div class="col-md-6">
            <h3>{{ $product->name }}</h3>
            <p>{{ $product->description }}</p>
            <p>Precio: ${{ $product->price }}</p>
        </div>
    </div>

    <hr>

    <!-- Sección de Comentarios de Clientes -->
    <div class="row">
        <div class="col-md-12">
            <h4>Comentarios de clientes</h4>
            @if($product->comments->isNotEmpty())
                <ul>
                    @foreach ($product->comments as $comment)
                        <li>
                            <strong>{{ $comment->user->name }}</strong> ({{ $comment->created_at->format('d/m/Y') }}):
                            <p>{{ $comment->content }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No hay comentarios para este producto.</p>
            @endif
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-primary">Volver al listado</a>
</div>

@stop
