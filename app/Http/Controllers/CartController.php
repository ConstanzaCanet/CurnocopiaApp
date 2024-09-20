<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{

    //utilizando Shoppingcart, que permite guardar el carrito tipo cookie---> la modificacion en base de datos se realizara en CheckOutController
    public function index()
    {
        // Obtener el carrito
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));

    }
    
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Añadir al carrito
        Cart::add($product->id, $product->name, 1, $product->price)
            ->associate('App\Models\Product');

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito!');
    }

    public function showCart()
    {
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));
    }

}
