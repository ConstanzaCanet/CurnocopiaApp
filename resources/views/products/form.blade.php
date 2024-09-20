@extends('adminlte::page')

@section('content')
    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PATCH')
        @endif

        <div class="mb-4 mt-4">
            <label for="name" class="block text-gray-700">Nombre del Producto:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
            @error('name')<br><span style="color: red;">{{"nombre es requerido"}}</span></br>@enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700">Descripción:</label>
            <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')<br><span style="color: red;">{{"una descripción es requerida"}}</span></br>@enderror
        </div>
        <div class="form-group">
            <label for="exampleInput">Category</label>
            <select class="form-control" id="category" name="category_id">
                @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                    >
                    {{ $category->category }}
                </option>
                @endforeach
            </select>
            @error('category')<br><span style="color: red;">{{"categoria es requerido"}}</span></br>@enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700">Precio:</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
            @error('price')<br><span style="color: red;">{{"una descripción es requerida"}}</span></br>@enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700">Stok:</label>
            <input type="number" name="stock_quantity" id="price" value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
            @error('stock_quantity')<br><span style="color: red;">{{"una descripción es requerida"}}</span></br>@enderror
        </div>

        <div class="mb-4">
            <label for="images" class="block text-gray-700">Imágenes del Producto:</label>
            <input type="file" name="images[]" id="images" multiple class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
            @error('images[]')<br><span style="color: red;">{{"Error al subir las imágenes"}}</span></br>@enderror
        </div>

        <!-- Botón de envío -->
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ isset($product) ? 'Actualizar Producto' : 'Crear Producto' }}
        </button>
    </form>


@stop