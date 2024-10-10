<?php

namespace App\Http\Controllers;

use Afip;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\AfipService;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class InvoiceController extends Controller
{
    
    protected $afipService;
    
    public function __construct(AfipService $afipService)
    {
        $this->afipService = $afipService;
    }

    public  function index()
    {
        $invoices = auth()->user()->orders()->response()->get();
        return view ('invoices.index',[
            'invoices' => $invoices
        ]);
    }
    
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $order = Order::where('id', $invoice->order_id)->first();
        if (!$order) {
            return redirect()->route('invoices.index')->with('error', 'La orden no existe.');
        }
        return view('invoices.show', compact('invoice', 'order'));
    }


    public function generate(Order $order)
    {   
        try
        {
        if (!$order) {
            return redirect()->route('invoices.index')->with('error', 'La orden no fue encontrada.');
        }
        $res = $this->afipService->createInvoice($order);
        // Guardar los datos del CAE y CAE vencimiento en la orden
        $order->update([
            'cae' => $res['CAE'],
            'cae_vto' => $res['CAEFchVto'],
            'status' => 'facturado'
        ]);
        Invoice::create([
            'order_id' => $order->id,
            'cae' => $res['CAE'],
            'cae_vto' => $res['CAEFchVto'],
        ]);
        
        return redirect()->route('invoices.show', $order->id)->with('success', 'Factura generada con éxito.');

        }catch( Exception $e)
        {dd($e);}

    }

    public function generatePdf($orderId)
    {
        $order = Order::with('user')->findOrFail($orderId);
        $user = $order->user;

        $pdf = PDF::loadView('invoices.pdf', compact('order', 'user'));

        return $pdf->download('invoice_' . $order->id . '.pdf');
    }   


}
