<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminStaff;

class AdminStaffController extends Controller
{
   public function index()
{
    return response()->json(
        AdminStaff::select(
            'admin_staff_id',
            'name',
            'email',
            'role'
        )
          ->where('role', 'Staff') // ✅ sirf Staff
        ->get()
    );

}

public function update(Request $request, $id)
{
    $user = AdminStaff::findOrFail($id);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => 'Staff'
    ]);

    return response()->json(['message' => 'Updated']);
}


public function destroy($id)
{
    $user = AdminStaff::find($id);

    if (!$user) {
        return response()->json(['message' => 'Not Found'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'Deleted Successfully']);
}

}