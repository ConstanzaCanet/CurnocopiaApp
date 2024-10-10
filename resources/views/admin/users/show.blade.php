@extends('adminlte::page')

@section('content')
    <div class="container">
        <h2 class="pt-4">Detalles del usuario: {{ $user->name }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
            </tbody>
        </table>
    
        <div>
            <h3>Enviar mensaje</h3>
            <form action="{{ route('admin.users.sendMessage', $user) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="subject">Asunto:</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Mensaje:</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
            </form>
        </div>
        
        <h3>Productos subidos</h3>
        @if($products->isNotEmpty())
            @foreach ($products as $product)
                <div class="shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="text-sm">{{ Str::limit($product->description, 50) }}</p>
                        <p class="text-lg font-bold">Precio: ${{ $product->price }}</p>
                        
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar producto</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <h2 class="text-gray-600">No ha publicado productos.</h2>
        @endif

    <h3>Compras realizadas</h3>
    @if($orders->isNotEmpty())
        @foreach ($orders as $order)
            <div class="shadow-md rounded-lg overflow-hidden">
                <div class="p-4">
                    <h3 class="text-lg font-semibold">Orden #{{ $order->id }}</h3>
                    <p class="text-sm">Total: ${{ $order->total_price }}</p>
                    <p class="text-sm">Fecha: {{ $order->created_at->format('d/m/Y') }}</p>
                    <p class="text-sm">Estado: {{ ucfirst($order->status) }}</p>
                    
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">Ver más</a>
                </div>
            </div>
        @endforeach
    @else
        <h2 class="text-gray-600">No ha realizado compras.</h2>
    @endif

    </div>
@endsection