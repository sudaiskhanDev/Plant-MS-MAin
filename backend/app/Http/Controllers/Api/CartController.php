<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    // 📥 GET ALL CART ITEMS (ALL USERS)
    public function index()
    {
        return response()->json(
            Cart::with('plant')->get()
        );
    }

    // ➕ CREATE OR UPDATE CART
    public function store(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|integer',
            'plant_id'  => 'required|integer|exists:plants,plant_id',
            'quantity'  => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', $request->user_id)
                    ->where('plant_id', $request->plant_id)
                    ->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            $cart = Cart::create($request->all());
        }

        return response()->json($cart);
    }

    // ✏️ UPDATE CART
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::findOrFail($id);
        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json($cart);
    }

    // ❌ DELETE SINGLE ITEM
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // 🧹 CLEAR ALL CART
    public function clear()
    {
        Cart::truncate();

        return response()->json(['message' => 'Cart cleared']);
    }
}