<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Products
    </h1>
</div>

<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6 lg:p-8">
    @if($products->isNotEmpty())
        <!-- Si hay productos los mostramos -->
        @foreach ($products as $product)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Nombre del producto -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600">{{ Str::limit($product->description, 50) }}</p>
                    <p class="text-lg font-bold text-blue-600 mt-2">Precio: ${{ $product->price }}</p>
                </div>

                <!-- Imagen del producto -->
                <div class="p-4">
                    @if($product->images->isNotEmpty())
                        @foreach ($product->images as $image)
                            <img src="{{ $image->path }}" alt="Imagen de {{ $product->name }}">
                        @endforeach
                    @else
                        <p class="text-gray-500">No hay imágenes disponibles para este producto.</p>
                    @endif
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center p-4 bg-gray-100">
                    
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 rounded-md py-2 px-4">
                        Agregar al carrito
                    </button>
                </form>

                    <button type="submit" class="rounded-md bg-red-600 py-2 px-4 text-white">
                        <a href="{{ route('products.edit', $product->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 rounded-md py-2 px-4 text-sm font-medium">
                            Editar
                        </a>
                    </button>

                    <!-- Formulario para eliminar producto -->
                    <form id="delete-product-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" onclick="return confirmDelete(event, {{ $product->id }})">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md bg-red-600 py-2 px-4 text-white">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <!-- Si no hay productos mostramos un mensaje -->
        <p class="text-gray-600">No hay productos disponibles.</p>
    @endif
    
</div>


<!-- Estilos personalizados -->
<style>
    .product-image {
        max-width: 10%;
        height: auto;
        object-fit: cover;
        border-radius: 0.375rem; /* Rounded corners for images */
    }
</style>

<script>

</script>
