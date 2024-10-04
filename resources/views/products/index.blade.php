@extends('adminlte::page')
@section('title', 'MyProdcuts')
@section('content')
<div class="wrapper">
    <div class="row">
        @session('success')
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endsession
    </div>

    <div class="p-6 lg:p-8 border-b border-gray-200">
        <h1>{{ request()->routeIs('products.my') ? 'Mis Productos' : 'Productos Disponibles' }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6 lg:p-8">
        @if($products->isNotEmpty())
            @foreach ($products as $product)
                <div class="shadow-md rounded-lg overflow-hidden">
                    <!-- Nombre del producto -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="text-sm">{{ Str::limit($product->description, 50) }}</p>
                        <p class="text-lg font-bold">Precio: ${{ $product->price }}</p>
                    </div>

                    <!-- Imagen del producto -->
                    <div class="p-4">
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
                    <!-- Botones de acción -->
                    @if($product->user_id !== auth()->id())
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-md py-2 px-4">
                                Agregar al carrito
                            </button>
                        </form>
                    @endif
                    @if($product->user_id == auth()->id() && request()->routeIs('products.my'))
                        <button type="submit" class="rounded-md py-2 px-4">
                            <a href="{{ route('products.edit', $product->id) }}">
                                Editar
                            </a>
                        </button>

                        <!-- Formulario para eliminar producto -->
                        <form id="delete-product-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" onclick="return confirmDelete(event, {{ $product->id }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md py-2 px-4">
                                Eliminar
                            </button>
                        </form>
                    @endif
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
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
@stop



<!-- Estilos personalizados -->
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
        event.preventDefault(); // Evitar el envío del formulario inmediato
    
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