<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductDeletedNotification;
use App\Notifications\UserMessageNotification;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            $users = User::paginate(20);
            return view('admin.index', compact('users'));
        }
        return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta página');
    }

    public function show(User $user)
    {
        $products = $user->products; 
        $orders = $user->orders; 
        return view('admin.users.show', compact('user', 'products', 'orders'));
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');

        Notification::send($user, new UserMessageNotification($subject, $message));

        return redirect()->back()->with('success', 'Mensaje enviado correctamente.');
    }

    public function destroyProduct(Product $product)
    {
        $user = $product->user;
        $product->delete();
        if ($user) {
            $user->notify(new ProductDeletedNotification($product));
        }
        return redirect()->back()->with('success', 'Product deleted.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted.');
    }
}
