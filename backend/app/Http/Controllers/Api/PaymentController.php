<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(Payment::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'amount' => 'required',
            'payment_date' => 'required|date',
            'payment_method' => 'required',
            'payment_status' => 'required'
        ]);

        $payment = Payment::create($request->all());

        return response()->json($payment, 201);
    }

    public function show($id)
    {
        return response()->json(Payment::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'order_id' => 'required',
            'amount' => 'required',
            'payment_date' => 'required|date',
            'payment_method' => 'required',
            'payment_status' => 'required'
        ]);

        $payment->update($request->all());

        return response()->json($payment);
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}