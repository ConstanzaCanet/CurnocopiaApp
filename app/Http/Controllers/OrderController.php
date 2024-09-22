<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;


use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;


class OrderController extends Controller
{
    //detalle de compra en card.checkout
    public function index()
    {
        $cartItems = Cart::content();
        $totalPrice = Cart::total();
        $user = auth()->user();  // Obtener el usuario autenticado

        return view('cart.checkout', compact('cartItems', 'totalPrice', 'user'));
    }

    public function create()
    {
        return view('orders.create');
    }




    public function store(Request $request)
    {
        // Calcula el precio total del carrito---> en mi caso he puesto necesario el total en modelo Order
        $totalPrice = Cart::content()->reduce(function ($total, $item) {
            return $total + ($item->qty * $item->price);
        }, 0);
       // $order = Order::create($request->except('token') + ['user_id' => Auth::id()] );

        // Crea la orden usando el $request excepto el token y agrega los datos faltantes
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
            //Redireccionar al pago de mercado pago
            return redirect($preference->init_point);

        }catch(\Exception $e){
            return redirect()->route('dashboard')->with('message', 'Ups! Algo salio mal!');
        }
    }
    
    
    public function callback(Order $order, Request $request)
    {
        
    }

}
