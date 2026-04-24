<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierOrder;

class SupplierOrderController extends Controller
{
    public function index()
    {
        return response()->json(SupplierOrder::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'plant_id' => 'required',
            'quantity' => 'required|integer',
            'delivery_status' => 'required'
        ]);

        $order = SupplierOrder::create($request->all());

        return response()->json($order, 201);
    }

    public function show($id)
    {
        return response()->json(SupplierOrder::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $order = SupplierOrder::findOrFail($id);

        $request->validate([
            'supplier_id' => 'required',
            'plant_id' => 'required',
            'quantity' => 'required|integer',
            'delivery_status' => 'required'
        ]);

        $order->update($request->all());

        return response()->json($order);
    }

    public function destroy($id)
    {
        SupplierOrder::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}