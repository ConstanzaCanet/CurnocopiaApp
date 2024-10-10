@extends('adminlte::page')

@section('content')
    <div class="text-center">

        <h2 class="pt-4">Edita tu producto</h2>
        <hr class="my-4">
        <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data" class="pt-4">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="mb-4 form-group">
                <label for="name" class="block text-gray-700">Nombre del Producto:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" class="form-control">
                @error('name')<br><span style="color: red;">{{"nombre es requerido"}}</span></br>@enderror
            </div>

            <div class="mb-4 form-group">
                <label for="description" class="block text-gray-700">Descripción:</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')<br><span style="color: red;">{{"una descripción es requerida"}}</span></br>@enderror
            </div>
            <div class="form-group">
                <label for="exampleInput">Category</label>
                <select class="custom-select" id="category" name="category_id">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        >
                        {{ $category->category }}
                    </option>
                    @endforeach
                </select>
                @error('category')<br><span style="color: red;">{{"categoria es requerido"}}</span></br>@enderror
            </div>

        <div class="form-row mb-4">
            <div class="col form-group">
                <label for="price" class="block text-gray-700">Precio:</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" class="form-control">
                @error('price')<br><span style="color: red;">{{"una descripción es requerida"}}</span></br>@enderror
            </div>

            <div class="col form-group">
                <label for="price" class="block text-gray-700">Stok:</label>
                <input type="number" name="stock_quantity" id="price" value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}" class="form-control">
                @error('stock_quantity')<br><span style="color: red;">{{"una descripción es requerida"}}</span></br>@enderror
            </div>
        </div>

            <div class="custom-file mb-4">
                <label for="images" class="btn btn-info">Imágenes del Producto:</label>
                <input type="file" name="images[]" id="images" multiple class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
                @error('images[]')<br><span style="color: red;">{{"Error al subir las imágenes"}}</span></br>@enderror
            </div>

            <button type="submit" class="btn btn-warning mt-4">
                {{ isset($product) ? 'Actualizar Producto' : 'Crear Producto' }}
            </button>
        </form>

        <hr class="my-5">

        <a href="{{ route('products.my') }}" class="btn btn-danger">Cancelar</a>
    </div>

@stop