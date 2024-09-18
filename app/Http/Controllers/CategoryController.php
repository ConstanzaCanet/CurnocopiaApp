<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();
        return view('products.form', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' =>'required|max:255',
            'description' => 'nullable'
        ]);

        Category::create($request->all());

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' =>'required|max:255',
            'description' => 'nullable'
        ]);

        $category = Category::find($id);
        $category->update($request->all());

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
    
}
