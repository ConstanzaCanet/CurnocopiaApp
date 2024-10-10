@extends('adminlte::page')

@section('content')
    <div class="container text-center">
        <div class="row">
            @session('success')
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endsession
        </div>

        <div>
            <h1 class="pt-4">{{ request()->routeIs('products.my') ? 'Mis Productos' : 'Productos Disponibles' }}</h1>
        </div>

        <hr class="my-4">

        <div class="row">
            @if($products->isNotEmpty())
                @foreach ($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-md rounded-lg overflow-hidden">
                            <div class="p-4 text-center">
                                @if($product->images->isNotEmpty())
                                    @if(auth()->user()->id !== $product->user_id)
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <img src="{{ $product->images->first()->path }}" class="img-fluid product-image" alt="Imagen de {{ $product->name }}">
                                        </a>
                                    @else
                                        <img src="{{ $product->images->first()->path }}" class="img-fluid product-image" alt="Imagen de {{ $product->name }}">
                                    @endif
                                @else
                                    <p>No hay imágenes disponibles para este producto.</p>
                                @endif
                            </div>
        
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-sm">{{ Str::limit($product->description, 50) }}</p>
                                <p class="text-lg font-bold">Precio: ${{ $product->price }}</p>
                            </div>

                            <div class="p-4">
                                @if($product->user_id !== auth()->id())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        Añadir al carrito
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-heart btn-block text-danger">
                                        <i class="fas fa-heart"></i> Wishlist
                                    </button>
                                </form>
                                @endif
        
                                @if($product->user_id == auth()->id() && request()->routeIs('products.my'))
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-block">Editar</a>
        
                                    <form id="delete-product-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" onclick="return confirmDelete(event, {{ $product->id }})">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h2 class="text-gray-600">No hay productos disponibles.</h2>
                <p>Empieza a vender subiendo tus productos!</p>
                <div class="mb-4">
                    <a href="{{ route('products.create') }}">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ingresar un producto
                        </button>
                    </a>
                </div>
            @endif
        
            <div class="content-center mt-4">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
            
    </div>
@stop



<style>
    .product-image {
        max-width: 10%;
        height: auto;
        object-fit: cover;
        border-radius: 0.375rem;
    }
</style>

<script>
    function confirmDelete(event, productId) {
        event.preventDefault();
        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esta acción.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-product-' + productId).submit();
            }
        });
    }

</script>