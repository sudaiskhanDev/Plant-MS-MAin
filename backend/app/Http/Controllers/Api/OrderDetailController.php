<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderDetailController extends Controller
{
    public function index()
    {
        return response()->json(OrderDetail::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'plant_id' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required'
        ]);

        $detail = OrderDetail::create($request->all());

        return response()->json($detail, 201);
    }

    public function show($id)
    {
        return response()->json(OrderDetail::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $detail = OrderDetail::findOrFail($id);

        $request->validate([
            'order_id' => 'required',
            'plant_id' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required'
        ]);

        $detail->update($request->all());

        return response()->json($detail);
    }

    public function destroy($id)
    {
        OrderDetail::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}