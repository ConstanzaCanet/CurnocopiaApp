<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function index()
    {
        // Retrieve the current user's cart items
        // Replace this with your own logic to fetch the cart items
        $cartItems = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 100, 'quantity' => 2],
            ['id' => 2, 'name' => 'Product 2', 'price' => 200, 'quantity' => 1],
        ];

        return view('cart', ['cartItems' => $cartItems]);
    }

    public function addItem(Request $request)
    {
        // Add the new item to the user's cart
        // Replace this with your own logic to add the item to the cart
        $cartItems = session('cartItems', []);
        $cartItems[] = $request->all();
        session(['cartItems' => $cartItems]);

        return redirect()->route('cart.index');
    }

    public function updateItem(Request $request, $itemId)
    {
        // Update the quantity of an item in the user's cart
        // Replace this with your own logic to update the item quantity
        $cartItems = session('cartItems', []);
        foreach ($cartItems as &$item) {
            if ($item['id'] === $itemId) {
                $item['quantity'] = $request->get('quantity');
                break;
            }
        }
        session(['cartItems' => $cartItems]);

        return redirect()->route('cart.index');
    }

    public function removeItem($itemId)
    {
        // Remove an item from the user's cart
        // Replace this with your own logic to remove the item from the cart
        $cartItems = session('cartItems', []);
        $cartItems = array_filter($cartItems, function ($item) use ($itemId) {
            return $item['id']!== $itemId;
        });
        session(['cartItems' => $cartItems]);

        return redirect()->route('cart.index');
    }
}
