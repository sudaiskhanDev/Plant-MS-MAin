<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        return response()->json(Cart::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'plant_id' => 'required',
            'quantity' => 'required|integer'
        ]);

        $cart = Cart::create($request->all());

        return response()->json($cart, 201);
    }

    public function show($id)
    {
        return response()->json(Cart::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        $request->validate([
            'user_id' => 'required',
            'plant_id' => 'required',
            'quantity' => 'required|integer'
        ]);

        $cart->update($request->all());

        return response()->json($cart);
    }

    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}