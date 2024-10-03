{{--<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
</x-app-layout>--}}

@extends('adminlte::page')

@section('title', 'Dashboard')



@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
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
    </div>
    

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
    
    </script>
@stop