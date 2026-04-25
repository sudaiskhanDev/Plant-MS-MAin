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
        )->get()
    );
}
}