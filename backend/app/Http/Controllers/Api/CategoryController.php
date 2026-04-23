<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories'
        ]);

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|unique:categories,category_name,' . $id . ',category_id'
        ]);

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}