<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlists()->with('product')->get();

        return view('wishlist.index', compact('wishlistItems'));
    }
    
    public function toggleWishlist(Request $request, $productId)
    {
        $user = auth()->user();
    
        $wishlistItem = $user->wishlists()->where('product_id', $productId)->first();
    
        if ($wishlistItem) {
            $wishlistItem->delete();
        } else {

            $user->wishlists()->create([
                'product_id' => $productId
            ]);
        }
    
        return redirect()->back()->with('message', 'Wishlist updated!');
    }

}
