<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all(); 
        return view('products.index', compact('products'));
    }

    public function create()
    {   
        return view('products.form',['categories'=>Category::all()]);
    }

    //Crear producto al recibir el request del formulario--->views>products>form
    public function store(Request $request)
    {
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
        
        return redirect()->route('dashboard')->with('success', 'Product created! successfull');
    }

    
    //Buscar por id del producto
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }
        return view('products.show', compact('product'));
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
        $search = $request->get('search');
        $products = Product::where('name', 'like', '%'. $search. '%');

        return view('products.index', compact('products'));
    }
    //busqueda por categoria
    public function filterByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId);
        $category = Category::find($categoryId);

        return view('products.index', compact('products', 'category'));
    }
    
    //Funcion para evitar repeticion
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


        // Mostrar los productos del usuario
        public function myProducts()
        {
            $products = Product::where('user_id', auth()->id())->get();
            return view('products.index', compact('products'));
        }
    
}
