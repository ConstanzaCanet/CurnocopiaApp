<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all(); // Cambia 10 por el número de productos por página
        return view('products.index', compact('products'));
    }

    public function create()
    {   
        return view('products.form',['categories'=>Category::all()]);
    }

    //Crear producto al recibir el request del formulario--->views>products>form
    public function store(Request $request)
    {
        // valido datos de request
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        // Crear el producto
        $product = Product::create($request->only('name', 'description', 'price', 'stock_quantity', 'category_id'));
        
        // Manejar las imágenes---> guardo en tabla aparte con la respectiva relacion de product-images
        $this->imageValidation($request->file('images'), $product);
        dd();
        
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
    

        // Actualizar el producto
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
        Storage::disk('public')->delete($image->path); // Eliminar la imagen del almacenamiento
        $image->delete(); // Eliminar el registro de la base de datos

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
                // Almacenar la imagen en el directorio 'product_images'
                $path = $image->store('product_images', 'public');
                
                // Depurar el path de la imagen
                Log::info('Imagen guardada en: ' . $path);
    
                // Crear el registro en la tabla 'images'
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
