@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Factura #{{ $order->id }}</h1>

    <p><strong>Fecha de emisión:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
    <p><strong>CAE:</strong> {{ $order->cae }}</p>
    <p><strong>Fecha de vencimiento del CAE:</strong> {{ $order->cae_vto }}</p>
    <p><strong>Total Pagado:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>


    <a href="{{ route('invoices.index') }}" class="btn btn-primary">Volver al historial de facturas</a>
    <a href="{{ route('invoices.pdf', $order->id) }}" class="btn btn-secondary">Descargar PDF</a>

</div>
@stop
