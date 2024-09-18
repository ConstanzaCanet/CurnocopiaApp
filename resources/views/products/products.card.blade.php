<div class="card">
    <img src="{{ $product->imagen }}" alt="{{ $product->name }}" class="card-img-top">
    <div class="card-body">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="card-text">{{ $product->description }}</p>
        <p class="card-text">Precio: ${{ $product->price }}</p>
        <a href="#" class="btn btn-primary">Ver más</a>
    </div>
</div>