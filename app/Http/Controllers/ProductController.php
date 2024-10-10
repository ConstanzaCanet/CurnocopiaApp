<?php

namespace App\Http\Controllers;

use App\Mail\CreateProductNotification;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::paginate(6); 
        return view('products.index', compact('products'));
    }

    public function create()
    {   
        return view('products.form',['categories'=>Category::all()]);
    }

    //Crear producto al recibir el request del formulario--->views>products>form
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);
    
            $product = Product::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'stock_quantity' => $request->input('stock_quantity'),
                'category_id' => $request->input('category_id'),
                'user_id' => auth()->id(),
            ]);
            
            // Manejar las imágenes---> guardo en tabla aparte con la respectiva relacion de product-images
            $this->imageValidation($request->file('images'), $product);
            
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->route('dashboard')->with('error', 'An error occurred while creating the product.');
        }
        
        Mail::to(auth()->user())->send(new CreateProductNotification($product));
        return redirect()->route('dashboard')->with('success', 'Product created! successfull');
    }

    
    //Buscar por id del producto
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $hasBoughtProduct = false;
    
        if (auth()->check()) {
            $user = auth()->user();
            $hasBoughtProduct = $user->orders()
                                    ->whereHas('items', function ($query) use ($product) {
                                        $query->where('product_id', $product->id);
                                    })
                                    ->exists();
        }
        return view('products.show', compact('product', 'hasBoughtProduct'));
    }

    //redireccionamiento al formulario de edicion con los datos del producto seleccionado
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::all();

        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }
        return view('products.form', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);
    
        $product->update($request->only('name', 'description', 'price', 'stock_quantity', 'category_id'));
        $this->imageValidation($request->file('images'), $product);

        return redirect()->route('products.show', $product->id)->with('success', 'Product updated successfully!');
    }
    
    //Eliminar productos
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }

    //Eliminar imagenes del producto
    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Imagen deleted.');
    }

    //busqueda especifica de producto
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                            ->paginate(6);
        return view('products.index', compact('products', 'searchTerm'));
    }

    //busqueda por categoria
    public function byCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate(6);
        return view('products.index', compact('category', 'products'));
    }
    
    // Mostrar los productos del usuario
    public function myProducts()
    {
        $products = Product::where('user_id', auth()->id())->paginate(6);
        return view('products.index', compact('products'));
    }
    

    public function hasBoughtProduct($productId)
    {
        return auth()->user()->orders()->whereHas('orderItems', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->exists();
    }
    
    //Funcion para evitar repeticion de código
    private function imageValidation($images,$product)
    {
        try {
            foreach ($images as $image) {
                $path = $image->store('product_images', 'public');
                //path de la imagen
                Log::info('Imagen guardada en: ' . $path);
    
                Image::create([
                    'path' => $path,
                    'product_id' => $product->id,
                ]);
    
                // Depurar para verificar si se creó la imagen
                Log::info('Imagen registrada en la base de datos para el producto: ' . $product->id);
            }
        } catch (\Exception $e) {
            Log::error('Error al subir las imágenes: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al subir las imágenes');
        }
    }
}
