<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));

    } 
    
    public function addToCart(Request $request, $id)
    {
        try
        {
            $product = Product::findOrFail($id);
            if ($product->stock_quantity < 1) {
                return redirect()->route('dashboard')->with('error', 'Producto sin stock!');
            }
            
            Cart::add($product->id, $product->name, 1, $product->price)
                ->associate(Product::class);
    
            return redirect()->route('dashboard')->with('success', 'Producto agregado al carrito!');
        }
        catch( Exception $e)
        {dd($e);}
        
    }

    public function showCart()
    {
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));
    }

    public function update(Request $request, $rowId)
    {
        $item = Cart::get($rowId);
        $product = Product::find($item->id);
    
        // Verificamos si hay suficiente stock
        if ($request->quantity > $product->stock_quantity) {
            return response()->json(['message' => 'La cantidad solicitada excede el stock disponible.'], 400);
        }
        // Si hay, actualizamos
        Cart::update($rowId, $request->quantity);
        return response()->json(['message' => 'Carrito actualizado con éxito.']);
    }
    
    public function destroy($rowId)
    {
        Cart::remove($rowId);
        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }
}
