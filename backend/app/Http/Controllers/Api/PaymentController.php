<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(Payment::all());
    }

    // ---------------- STRIPE INTENT ----------------
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => $request->amount * 100,
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret
        ]);
    }

    // ---------------- SAVE PAYMENT (after success) ----------------
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'amount' => 'required|numeric',
            'stripe_payment_id' => 'required|string',
            'payment_method' => 'required',
            'payment_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $payment = Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
        ]);

        return response()->json([
            'message' => 'Payment saved successfully',
            'payment' => $payment
        ], 201);
    }

    public function show($id)
    {
        return response()->json(Payment::findOrFail($id));
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Payment;

// class PaymentController extends Controller
// {
//     public function index()
//     {
//         return response()->json(Payment::all());
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'order_id' => 'required',
//             'amount' => 'required',
//             'payment_date' => 'required|date',
//             'payment_method' => 'required',
//             'payment_status' => 'required'
//         ]);

//         $payment = Payment::create($request->all());

//         return response()->json($payment, 201);
//     }

//     public function show($id)
//     {
//         return response()->json(Payment::findOrFail($id));
//     }

//     public function update(Request $request, $id)
//     {
//         $payment = Payment::findOrFail($id);

//         $request->validate([
//             'order_id' => 'required',
//             'amount' => 'required',
//             'payment_date' => 'required|date',
//             'payment_method' => 'required',
//             'payment_status' => 'required'
//         ]);

//         $payment->update($request->all());

//         return response()->json($payment);
//     }

//     public function destroy($id)
//     {
//         Payment::findOrFail($id)->delete();

//         return response()->json(['message' => 'Deleted']);
//     }
// }