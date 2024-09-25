<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public  function index()
    {
        $invoices = auth()->user()->orders()->response()->get();
        return view ('invoices.index',[
            'invoices' => $invoices
        ]);
    }
    
    public function show(Order $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

}
