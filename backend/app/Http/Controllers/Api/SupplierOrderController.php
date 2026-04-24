<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplierOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierOrderController extends Controller
{
    public function index()
    {
        return response()->json(
            SupplierOrder::with(['supplier', 'plant'])->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'plant_id' => 'required|exists:plants,plant_id',
            'quantity' => 'required|integer|min:1',
            'delivery_status' => 'required|in:pending,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = SupplierOrder::create($request->all());

        return response()->json([
            'message' => 'Supplier order created',
            'data' => $order
        ], 201);
    }

    public function show($id)
    {
        $order = SupplierOrder::with(['supplier', 'plant'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = SupplierOrder::find($id);

        if (!$order) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $order->update($request->all());

        return response()->json([
            'message' => 'Updated',
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        $order = SupplierOrder::find($id);

        if (!$order) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Deleted']);
    }
}