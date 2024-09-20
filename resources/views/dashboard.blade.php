{{--<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
</x-app-layout>--}}

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{env('APP_NAME')}}</h1>
@stop

@section('content')
    <div class="row">
        @session('success')
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endsession
    </div>
    <div class="container-fluid">
        <p>HOME</p>
        
        <!-- Botón para agregar producto -->
        <div class="mb-4">
            <a href="{{ route('products.create') }}">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ingresar un producto
                </button>
            </a>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Productos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Productos -->
                            @if(request()->routeIs('products.show'))
                                @include('products.show', ['product' => $product])
                            @else
                                @include('products.index', ['products' => $products])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
    
    <!-- SweetAlert para confirmar eliminación -->
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
                    document.getElementById('delete-product').submit();
                }
            });
        }
        //cart
        Array.from(document.querySelectorAll('.addToCart')).forEach(function(item){

        })


    </script>
@stop

@section('css')
    <style>
        .card-body {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .product-card {
            flex: 1 1 200px;
            max-width: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 1rem;
            text-align: center;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('.sidebar-toggle').click(function () {
                $('body').toggleClass('sidebar-collapse');
            });
        });
        // Confirmación para eliminar un producto
        function confirmDelete(event, productId) {
            event.preventDefault();

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡No podrás revertir esta acción!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, se envía el formulario
                document.getElementById('delete-product-' + productId).submit();
            }
            })
        }
    
    </script>
@stop