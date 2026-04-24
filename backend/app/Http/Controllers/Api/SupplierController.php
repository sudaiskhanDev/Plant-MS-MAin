<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json(Supplier::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'address' => 'required'
        ]);

        $supplier = Supplier::create($request->all());

        return response()->json($supplier, 201);
    }

    public function show($id)
    {
        return response()->json(Supplier::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'contact' => 'required',
            'address' => 'required'
        ]);

        $supplier->update($request->all());

        return response()->json($supplier);
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}