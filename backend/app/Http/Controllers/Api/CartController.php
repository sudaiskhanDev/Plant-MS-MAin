<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    // GET CART (ONLY LOGGED IN USER)
    public function index()
    {
        return response()->json(
            Cart::with('plant')
                ->where('user_id', auth()->id())
                ->get()
        );
    }

    // ADD TO CART (FIXED)

    public function store(Request $request)
{
    $request->validate([
        'plant_id' => 'required|exists:plants,plant_id',
        'quantity' => 'required|integer|min:1'
    ]);

    $userId = auth()->id();

    $cart = Cart::where('user_id', $userId)
                ->where('plant_id', $request->plant_id)
                ->first();

    if ($cart) {
        // already exists → increase quantity
        $cart->quantity += $request->quantity;
        $cart->save();
    } else {
        // new item
        $cart = Cart::create([
            'user_id' => $userId,
            'plant_id' => $request->plant_id,
            'quantity' => $request->quantity
        ]);
    }

    return response()->json([
        'message' => 'Cart updated',
        'cart' => $cart
    ]);
}
//     public function store(Request $request)
//     {
//         $request->validate([
//             'plant_id' => 'required',
//             'quantity' => 'required|integer|min:1'
//         ]);

//         $userId = auth()->id();

//         // check if already exists
//         $cart = Cart::create([
//     'user_id' => auth()->id(),
//     'plant_id' => $request->plant_id,
//     'quantity' => $request->quantity
// ]);

//         if ($cart) {
//             $cart->quantity += $request->quantity;
//             $cart->save();
//         } else {
//             $cart = Cart::create([
//                 'user_id' => $userId,
//                 'plant_id' => $request->plant_id,
//                 'quantity' => $request->quantity
//             ]);
//         }

//         return response()->json($cart, 201);
//     }

    public function show($id)
    {
        return response()->json(
            Cart::with('plant')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', auth()->id())
                    ->where('cart_id', $id)
                    ->firstOrFail();

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json($cart);
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', auth()->id())
                    ->where('cart_id', $id)
                    ->firstOrFail();

        $cart->delete();

        return response()->json(['message' => 'Deleted']);
    }
}




// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Cart;

// class CartController extends Controller
// {
//     public function index()
//     {
//         return response()->json(Cart::all());
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'user_id' => 'required',
//             'plant_id' => 'required',
//             'quantity' => 'required|integer'
//         ]);

//         $cart = Cart::create($request->all());

//         return response()->json($cart, 201);
//     }

//     public function show($id)
//     {
//         return response()->json(Cart::findOrFail($id));
//     }

//     public function update(Request $request, $id)
//     {
//         $cart = Cart::findOrFail($id);

//         $request->validate([
//             'user_id' => 'required',
//             'plant_id' => 'required',
//             'quantity' => 'required|integer'
//         ]);

//         $cart->update($request->all());

//         return response()->json($cart);
//     }

//     public function destroy($id)
//     {
//         Cart::findOrFail($id)->delete();

//         return response()->json(['message' => 'Deleted']);
//     }
// }