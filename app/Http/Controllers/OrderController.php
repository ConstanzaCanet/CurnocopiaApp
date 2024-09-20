<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
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
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $request->total_price,
            'shipping_address' => $request->shipping_address,
        ]);

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price_at_purchase' => $product->price,
            ]);

            // Reducir stock del producto
            $product->decrement('stock_quantity', $productData['quantity']);
        }

        return redirect()->route('orders.index')->with('success', 'Order placed successfully');
    }
}
