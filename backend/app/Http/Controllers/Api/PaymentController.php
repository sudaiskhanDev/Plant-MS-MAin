<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(Payment::with('order')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
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
            'message' => 'Payment created',
            'data' => $payment
        ], 201);
    }

    public function show($id)
    {
        $payment = Payment::with('order')->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $payment->update($request->all());

        return response()->json([
            'message' => 'Updated',
            'data' => $payment
        ]);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $payment->delete();

        return response()->json(['message' => 'Deleted']);
    }
}