<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $productId = request('product_id');
        $comments = Comment::where('product_id', $productId)->with('user')->latest()->get();
    
        return view('products.show', compact('comments'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
        if (auth()->check()) {
   
            $user = auth()->user();
            $comment = new Comment();
            $comment->content = $request->input('content');
            $comment->user_id = $user->id;
            $comment->product_id = $productId;
            $comment->save();
    
            return back()->with('success', 'Comentario agregado exitosamente.');
        }
    
        return back()->with('error', 'Debes iniciar sesión para comentar.');
    }
    
}
