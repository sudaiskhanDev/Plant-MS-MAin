<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'zip' => 'nullable|string',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,card',
            'payment_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItems = Cart::with('plant')
            ->where('user_id', $user->user_id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();

        try {

            // 💰 TOTAL CALCULATION
            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->plant->price * $item->quantity;
            }

            // 📦 CREATE ORDER
            $order = Order::create([
                'user_id' => $user->user_id,
                'order_date' => now(),
                'status' => $request->payment_method === 'cod' ? 'pending' : 'paid',
                'total_amount' => $total,
                'payment_method' => $request->payment_method,

                'name' => $request->name,
                'phone' => $request->phone,
                'city' => $request->city,
                'zip' => $request->zip,
                'shipping_address' => $request->shipping_address,
            ]);

            // 📦 ORDER ITEMS
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'plant_id' => $item->plant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->plant->price,
                ]);
            }

            $paymentStatus = 'pending';

            // 💳 STRIPE PAYMENT
            if ($request->payment_method === 'card') {

                Stripe::setApiKey(env('STRIPE_SECRET'));

                $paymentIntent = PaymentIntent::create([
                    'amount' => $total * 100, // cents
                    'currency' => 'usd',
                    'payment_method' => $request->payment_id,
                    'confirm' => true,
                ]);

                $paymentStatus = $paymentIntent->status;

                Payment::create([
                    'order_id' => $order->order_id,
                    'amount' => $total,
                    'payment_method' => 'card',
                    'payment_status' => $paymentStatus,
                ]);
            }

            // 🧾 COD PAYMENT
            if ($request->payment_method === 'cod') {
                Payment::create([
                    'order_id' => $order->order_id,
                    'amount' => $total,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                ]);
            }

            // 🧹 CLEAR CART
            Cart::where('user_id', $user->user_id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order
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













// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Cart;
// use App\Models\Order;
// use App\Models\OrderDetail;
// use App\Models\Payment;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;

// class CheckoutController extends Controller
// {
//     public function checkout(Request $request)
//     {
//         $user = auth()->user();

//         if (!$user) {
//             return response()->json(['message' => 'Unauthorized'], 401);
//         }

//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'phone' => 'required|string|max:20',
//             'city' => 'required|string|max:100',
//             'zip' => 'nullable|string|max:20',
//             'shipping_address' => 'required|string',

//             'payment_method' => 'required|in:cod,card',
//             'payment_id' => 'nullable|string'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'message' => 'Validation failed',
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         $cartItems = Cart::with('plant')
//             ->where('user_id', $user->user_id)
//             ->get();

//         if ($cartItems->isEmpty()) {
//             return response()->json(['message' => 'Cart is empty'], 400);
//         }

//         DB::beginTransaction();

//         try {

//             // total calculation
//             $total = 0;

//             foreach ($cartItems as $item) {
//                 if (!$item->plant) {
//                     return response()->json(['message' => 'Product missing'], 400);
//                 }
//                 $total += $item->plant->price * $item->quantity;
//             }

//             // create order
//             $order = Order::create([
//                 'user_id' => $user->user_id,
//                 'order_date' => now(),
//                 'status' => $request->payment_method === 'cod' ? 'pending' : 'paid',
//                 'total_amount' => $total,
//                 'payment_method' => $request->payment_method,

//                 // shipping
//                 'name' => $request->name,
//                 'phone' => $request->phone,
//                 'city' => $request->city,
//                 'zip' => $request->zip,
//                 'shipping_address' => $request->shipping_address,
//             ]);

//             // order details
//             foreach ($cartItems as $item) {
//                 OrderDetail::create([
//                     'order_id' => $order->order_id,
//                     'plant_id' => $item->plant_id,
//                     'quantity' => $item->quantity,
//                     'price' => $item->plant->price,
//                 ]);
//             }

//             // payment
//             if ($request->payment_method === 'card' && $request->payment_id) {
//                 Payment::create([
//                     'order_id' => $order->order_id,
//                     'amount' => $total,
//                     'payment_method' => 'card',
//                     'payment_status' => 'completed',
//                 ]);
//             }

//             // clear cart
//             Cart::where('user_id', $user->user_id)->delete();

//             DB::commit();

//             return response()->json([
//                 'message' => 'Order placed successfully',
//                 'order' => $order
//             ], 201);

//         } catch (\Exception $e) {
//             DB::rollBack();

//             return response()->json([
//                 'message' => 'Checkout failed',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }
// }
// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Cart;
// use App\Models\Order;
// use App\Models\OrderDetail;
// use App\Models\Payment;
// use Illuminate\Support\Facades\DB;

// class CheckoutController extends Controller
// {
//     public function checkout(Request $request)
//     {
//         $user = auth()->user();

//         if (!$user) {
//             return response()->json(['message' => 'Unauthorized'], 401);
//         }

//         $request->validate([
//             'shipping_address' => 'required|string',
//             'phone' => 'required|string',
//             'payment_method' => 'required|in:cod,card',
//             'payment_id' => 'nullable|string',
//         ]);

//         $cartItems = Cart::with('plant')
//             ->where('user_id', $user->user_id)
//             ->get();

//         if ($cartItems->isEmpty()) {
//             return response()->json(['message' => 'Cart is empty'], 400);
//         }

//         DB::beginTransaction();

//         try {

//             // ================= TOTAL CALCULATION =================
//             $total = 0;

//             foreach ($cartItems as $item) {
//                 $total += $item->plant->price * $item->quantity;
//             }

//             // ================= CREATE ORDER =================
//             $order = Order::create([
//                 'user_id' => $user->user_id,
//                 'order_date' => now(),
//                 'total_amount' => $total,
//                 'status' => $request->payment_method === 'cod' ? 'pending' : 'paid',
//             ]);

//             // ================= ORDER DETAILS =================
//             foreach ($cartItems as $item) {
//                 OrderDetail::create([
//                     'order_id' => $order->order_id,
//                     'plant_id' => $item->plant_id,
//                     'quantity' => $item->quantity,
//                     'price' => $item->plant->price,
//                 ]);
//             }

//             // ================= PAYMENT =================
//             if ($request->payment_method === 'card') {

//                 Payment::create([
//                     'order_id' => $order->order_id,
//                     'amount' => $total,
//                     'payment_date' => now(),
//                     'payment_method' => 'card',
//                     'payment_status' => 'completed',
//                 ]);
//             } else {

//                 Payment::create([
//                     'order_id' => $order->order_id,
//                     'amount' => $total,
//                     'payment_date' => now(),
//                     'payment_method' => 'cod',
//                     'payment_status' => 'pending',
//                 ]);
//             }

//             // ================= CLEAR CART =================
//             Cart::where('user_id', $user->user_id)->delete();

//             DB::commit();

//             return response()->json([
//                 'message' => 'Order placed successfully',
//                 'order' => $order,
//                 'total' => $total
//             ], 201);

//         } catch (\Exception $e) {
//             DB::rollBack();

//             return response()->json([
//                 'message' => 'Checkout failed',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }
// }