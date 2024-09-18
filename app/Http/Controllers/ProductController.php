<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        // Pass the products to the view
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
        $validatedData = $request->validate([
            'name' =>'required|max:255',
            'price' =>'required|numeric',
            'description' =>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_quantity' =>'required|numeric',
            'category_id' =>'required'
        ]);
        //creo en base de datos
        Product::create($validatedData);
        return redirect('dashboard')->with('success', 'Product created successfully!');
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

        $validatedData = $request->validate([
            'name' =>'required|max:255',
            'price' =>'required|numeric',
            'description' =>'nullable',
            //'image' => 'nullable|image|max:2048',
            'stock_quantity' =>'required|integer',
            'category_id' =>'nullable'
        ]);

        $product->update($validatedData);

        return redirect()->route('products.show', $product->id)->with('success', 'Product updated successfully!');
    }
    
    //Eliminar productos
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }
    //busqueda especifica de producto
    public function search(Request $request)
    {
        $search = $request->get('search');
        $products = Product::where('name', 'like', '%'. $search. '%')->paginate(10);

        return view('products.index', compact('products'));
    }
    //busqueda por categoria
    public function filterByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->paginate(10);
        $category = Category::find($categoryId);

        return view('products.index', compact('products', 'category'));
    }
    
}
