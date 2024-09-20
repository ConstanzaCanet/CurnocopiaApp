<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function storeOrder()
    {
        // Crear la orden del usuario autenticado
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => Cart::total(),
            'status' => 'pending', // o 'completed'
        ]);

        // Crear los OrderItems basados en el contenido del carrito
        foreach (Cart::content() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'price' => $item->price,
            ]);
        }

        // Vaciar el carrito
        Cart::destroy();

        // Redirigir al resumen de la orden
        return redirect()->route('orders.show', $order->id)->with('success', '¡Orden completada!');
    }
}
