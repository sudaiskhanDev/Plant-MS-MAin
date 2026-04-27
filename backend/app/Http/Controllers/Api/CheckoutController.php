<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'zip' => 'nullable|string',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,card',
            'payment_id' => 'nullable|string',
        ]);

        $cartItems = Cart::with('plant')
    ->where('user_id', $user->user_id)
    ->whereHas('plant') // 🔥 THIS LINE FIXES EVERYTHING
    ->get();
        // $cartItems = Cart::with('plant')
        //     ->where('user_id', $user->user_id)
        //     ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();

        try {

            // ================= TOTAL =================
            $total = 0;

            foreach ($cartItems as $item) {
                if (!$item->plant) {
                    DB::rollBack();
                    return response()->json(['message' => 'Product missing in cart'], 400);
                }

                $total += $item->plant->price * $item->quantity;
            }

            // ================= ORDER =================
            $order = Order::create([
                'user_id' => $user->user_id,
                'order_date' => now(),
                'status' => 'pending',
                'total_amount' => $total,

                // shipping
                'name' => $request->name,
                'phone' => $request->phone,
                'city' => $request->city,
                'zip' => $request->zip,
                'shipping_address' => $request->shipping_address,

                // keep consistency
                'payment_method' => $request->payment_method,
            ]);

            // ================= ORDER DETAILS =================
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'plant_id' => $item->plant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->plant->price,
                ]);
            }

            // ================= PAYMENT =================
            Payment::create([
                'order_id' => $order->order_id,
                'amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' =>
                    $request->payment_method === 'cod' ? 'pending' : 'completed',
                'payment_date' => now(),
                'stripe_payment_id' => $request->payment_id ?? null,
            ]);

            // ================= UPDATE STATUS =================
            $order->update([
                'status' => $request->payment_method === 'cod' ? 'pending' : 'paid'
            ]);

            // ================= CLEAR CART =================
            Cart::where('user_id', $user->user_id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order,
                'total' => $total
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}