<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /* =========================
       GET ALL ORDERS (ADMIN/FRONTEND)
    ========================= */
    public function index()
    {
        $orders = Order::with('details')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /* =========================
       CREATE ORDER FROM CART (NO AUTH)
    ========================= */
    public function store(Request $request)
{
    $order = Order::create([
        'user_id' => $request->user_id,
        'total_amount' => $request->total_amount,
        'status' => $request->status ?? 'pending'
    ]);

    return response()->json([
        'message' => 'Order created successfully',
        'data' => $order
    ], 201);
}

    /* =========================
       SHOW SINGLE ORDER
    ========================= */
    public function show($id)
    {
        $order = Order::with('details')->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json($order);
    }

    /* =========================
       UPDATE ORDER
    ========================= */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /* =========================
       DELETE (CANCEL ORDER)
    ========================= */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Order cancelled successfully'
        ]);
    }
}