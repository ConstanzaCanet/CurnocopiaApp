@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="container text-center">
            <h2 class="pt-4">Detalles del usuario: {{ $user->name }}</h2>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
            </tbody>
        </table>

        <hr class="my-4">

        @include('admin.users.sendMessage', $user)

        <hr class="my-4">

        <h3>Productos subidos</h3>
        @if($products->isNotEmpty())
            @foreach ($products as $product)
                <div class="row">
                    <div class="col-6 p-4 border">
                        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="text-sm">{{ Str::limit($product->description, 50) }}</p>
                        <p class="text-lg font-bold">Precio: ${{ $product->price }}</p>
                        
                        <form id="delete-product-{{ $product->id }}" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onclick="return confirmDelete(event, {{ $product->id }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <h2 class="text-gray-600">No ha publicado productos.</h2>
        @endif

        <hr class="my-4">
        
    <h3>Compras realizadas</h3>
    @if($orders->isNotEmpty())
        @foreach ($orders as $order)
            <div class="row">
                <div class="col-6 p-4">
                    <h3 class="text-lg font-semibold">Orden #{{ $order->id }}</h3>
                    <p class="text-sm">Total: ${{ $order->total_price }}</p>
                    <p class="text-sm">Fecha: {{ $order->created_at->format('d/m/Y') }}</p>
                    <p class="text-sm">Estado: {{ ucfirst($order->status) }}</p>
                    
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">Ver más</a>
                </div>
            </div>
        @endforeach
    @else
        <h4 class="p-3">No ha realizado compras.</h4>
    @endif

    </div>
@endsection

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