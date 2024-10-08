@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Mis Facturas</h1>
    <p>Aquí puedes ver el historial de tus pedidos y su facturación.</p>

    @if($invoices->isEmpty())
        <p>No tienes facturas disponibles.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Fecha</th>
                    <th>Total Pagado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td> 
                    <td>{{ $invoice->created_at->format('d/m/Y H:i') }}</td> 
                    <td>${{ number_format($invoice->total_price, 2) }}</td> 
                    <td>{{ ucfirst($invoice->status) }}</td>
                    <td>
                    @if ($invoice->status == 'facturado')
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-success">Facturado</a>
                    @else
                        <a href="{{ route('invoices.generate', $invoice->id) }}" class="btn btn-primary">Facturar</a>
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>



@stop