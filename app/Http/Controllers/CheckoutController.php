<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::content();
        return view('cart.checkout', compact('cartItems'));
    }
    public function storeOrder()
    {
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => Cart::total(),
            'status' => 'pending',
        ]);

        foreach (Cart::content() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'price' => $item->price,
            ]);
        }
        Cart::destroy();

        return redirect()->route('orders.show', $order->id)->with('success', '¡Orden completada!');
    }
}
