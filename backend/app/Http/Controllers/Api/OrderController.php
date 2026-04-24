<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_date' => 'required|date',
            'total_amount' => 'required',
            'status' => 'required',
            'user_id' => 'required'
        ]);

        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    public function show($id)
    {
        return response()->json(Order::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'order_date' => 'required|date',
            'total_amount' => 'required',
            'status' => 'required',
            'user_id' => 'required'
        ]);

        $order->update($request->all());

        return response()->json($order);
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}