<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $token = auth('user_api')->login($user);

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('user_api')->factory()->getTTL() * 60
    ]);
}
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user_api')->factory()->getTTL() * 60
        ]);
    }

    public function me()
    {
        return response()->json(auth('user_api')->user());
    }

    public function logout()
    {
        auth('user_api')->logout();
        return response()->json(['message' => 'Logged out']);
    }
}