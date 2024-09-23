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
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;

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
        if($order->preference == $request->preference){
            $order->update(['api_response' => $request->all()]);
            $paymentStatus = $request->status; // 'approved', 'pending', 'failure', etc.
            // Obtén el estado del pago desde la respuesta

        // Valida si el pago fue aprobado
        if ($paymentStatus == 'approved') {
            // Actualiza el estado del pedido a pagado
            $order->update(['status' => 'paid']);

            // Elimina el carrito
            Cart::destroy();

            // Envía el correo de confirmación de pago
            Mail::to($order->user->email)->send(new PaymentConfirmed($order));

            return redirect('dashboard')->with('success','se ha tomado el pago de tu compra');
        }
        dd($request->all());
        
            // Si el pago no fue aprobado, redirigir a una página de estado
            if ($paymentStatus == 'pending') {
                return redirect()->route('dashboard')->with('warning', 'Tu pago está pendiente. Te notificaremos una vez se acredite.');
            }
    
            if ($paymentStatus == 'failure') {
                return redirect()->route('dashboard')->with('error', 'El pago ha fallado. Por favor, intenta nuevamente.');
            }
        }
            // Si la preferencia no coincide, redirige con error
        return redirect()->route('dashboard')->with('error', 'No se pudo procesar tu pago. Por favor, intenta nuevamente.');

    }
}