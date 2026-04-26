<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // 🔵 ADMIN: ALL NOTIFICATIONS
    public function index()
    {
        return response()->json(Notification::all());
    }

    // ➕ CREATE (Admin)
    public function store(Request $request)
    {
        $request->validate([
            'admin_staff_id' => 'required',
            'message' => 'required',
            'type' => 'required',
            'date' => 'required|date'
        ]);

        return Notification::create($request->all());
    }

    public function show($id)
    {
        return Notification::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update($request->all());

        return response()->json($notification);
    }

    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // 🔥 STAFF: ONLY HIS NOTIFICATIONS
    public function myNotifications()
    {
        $staff = auth('admin')->user();

        if (!$staff) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return Notification::where('admin_staff_id', $staff->admin_staff_id)
            ->latest()
            ->get();
    }
}





// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Notification;

// class NotificationController extends Controller
// {
//     public function index()
//     {
//         return response()->json(Notification::all());
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'admin_staff_id' => 'required',
//             'message' => 'required',
//             'type' => 'required',
//             'date' => 'required|date'
//         ]);

//         $notification = Notification::create($request->all());

//         return response()->json($notification, 201);
//     }

//     public function show($id)
//     {
//         return response()->json(Notification::findOrFail($id));
//     }

//     public function update(Request $request, $id)
//     {
//         $notification = Notification::findOrFail($id);

//         $request->validate([
//             'admin_staff_id' => 'required',
//             'message' => 'required',
//             'type' => 'required',
//             'date' => 'required|date'
//         ]);

//         $notification->update($request->all());

//         return response()->json($notification);
//     }

//     public function destroy($id)
//     {
//         Notification::findOrFail($id)->delete();

//         return response()->json(['message' => 'Deleted']);
//     }
// }