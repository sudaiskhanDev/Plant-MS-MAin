<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderDetailController extends Controller
{
     use App\Models\OrderDetail;

public function store()
{
    $userId = auth('user_api')->id();

    $cartItems = Cart::where('user_id', $userId)->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'Cart is empty'], 400);
    }

    DB::beginTransaction();

    try {
        $total = 0;

        foreach ($cartItems as $item) {
            $plant = Plant::find($item->plant_id);

            if ($plant->stock_quantity < $item->quantity) {
                throw new \Exception("Stock not available for {$plant->name}");
            }

            $total += $plant->price * $item->quantity;
        }

        // 🧾 CREATE ORDER
        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // 🔥 INSERT ORDER DETAILS + STOCK REDUCE
        foreach ($cartItems as $item) {
            $plant = Plant::find($item->plant_id);

            OrderDetail::create([
                'order_id' => $order->order_id,
                'plant_id' => $plant->plant_id,
                'quantity' => $item->quantity,
                'price' => $plant->price
            ]);

            // 📉 reduce stock
            $plant->stock_quantity -= $item->quantity;
            $plant->save();
        }

        // 🧹 CLEAR CART
        Cart::where('user_id', $userId)->delete();

        DB::commit();

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
