<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminStaff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admin_staff',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,Staff'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = AdminStaff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User created', 'user' => $user]);
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!$token = auth('admin')->attempt($credentials)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $user = auth('admin')->user();

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('admin')->factory()->getTTL() * 60,
        'user' => [
            'id' => $user->admin_staff_id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);
}

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out']);
    }
}