<?php
namespace App\Http\Controllers;

use App\Mail\PaymentConfirmed;
use App\Mail\SendInvoice;
use App\Models\Order;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

use App\Services\AfipService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class OrderController extends Controller
{
    protected $afipService;

    public function __construct(AfipService $afipService)
    {
        $this->afipService = $afipService;
    }

    //detalle de compra en card.checkout
    public function index()
    {
        $cartItems = Cart::content();
        $totalPrice = Cart::total();
        $user = Auth::user();

        return view('cart.checkout', compact('cartItems', 'totalPrice', 'user'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $totalPrice = Cart::content()->reduce(function ($total, $item) {
            return $total + ($item->qty * $item->price);
        }, 0);

        $order = Order::create(
        array_merge($request->except('token'), [
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'uuid' => Str::uuid(),
            'status' => 'pending',
        ])
        );


        //retorna datos pata mercado pago
        $items = Cart::content()->map(function($item) use ($order){
            $orderDetail = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'price_at_purchase' => $item->price,
            ]);
            return [
                'id' => "PROD-($orderDetail->id)",
                'title' => $orderDetail->product->name,
                'quantity' => (int) $orderDetail->quantity,
                'unit_price' => (float) $orderDetail->price_at_purchase,
            ];
        })->values()->toArray();
        
        //configuro el mercado pago
        MercadoPagoConfig::setAccessToken(config('services.mercado_pago.token'));
        $client = new PreferenceClient;

        try{
            $preference = $client->create([
                "items" => $items,
                "auto_return" => "approved",
                "back_urls" => [
                    "success" => route('config', ['order' => $order]),//cuando pasa
                    "failure" => route('config', ['order' => $order]),//cuando falla
                    "pending" => route('config', ['order' => $order]),//cuando queda pendiente
                ],
                "statment_description" => "cornocopia almacen"
            ]);
            
            $order->update(['preference' => $preference->id]);
            return redirect($preference->init_point);

        }catch(\Exception $e){
            return redirect()->route('dashboard')->with('message', 'Ups! Algo salio mal!');
        }
    }
    
    public function callback(Order $order, Request $request)
    {
            if($order->preference == $request->preference){
                $order->update(['api_response' => $request->all()]);
            }
            Mail::to(auth()->user())->send(new PaymentConfirmed($order,auth()->user()));
            Cart::destroy();
            return redirect('dashboard')->with('success','se ha tomado el pago de tu compra');
    }

    public function show(Order $order)
    {
        $user = $order->user;
        return view('admin.orders.show', compact('order','user'));
    }


    public function sendInvoice(Order $order)
    {
        $user = $order->user;
        Mail::to($user->email)->send(new SendInvoice($order));
        return redirect()->back()->with('success', 'Factura enviada al usuario.');
    }

}
