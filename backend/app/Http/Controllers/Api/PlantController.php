<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant;

class PlantController extends Controller
{
    public function index()
    {
        return response()->json(
            Plant::with('category')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image'
        ]);

        $data = $request->all();

        // 🖼️ IMAGE UPLOAD
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('plants', 'public');
            $data['image'] = $path;
        }

        $plant = Plant::create($data);

        return response()->json($plant, 201);
    }

    public function show($id)
    {
        return response()->json(
            Plant::with('category')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('plants', 'public');
            $data['image'] = $path;
        }

        $plant->update($data);

        return response()->json($plant);
    }

    public function destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();

        return response()->json(['message' => 'Deleted']);
    }
}