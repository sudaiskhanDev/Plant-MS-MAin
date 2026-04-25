<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    // 📥 GET ALL (Admin use)
    public function index()
    {
        return response()->json(
            Maintenance::with(['plant', 'staff'])->get()
        );
    }

    // ➕ CREATE (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,plant_id',
            'admin_staff_id' => 'required|exists:admin_staff,admin_staff_id',
            'task_type' => 'required|string',
            'scheduled_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $maintenance = Maintenance::create([
            'plant_id' => $request->plant_id,
            'admin_staff_id' => $request->admin_staff_id,
            'task_type' => $request->task_type,
            'scheduled_date' => $request->scheduled_date,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Maintenance created successfully',
            'data' => $maintenance
        ], 201);
    }

    // 📄 SINGLE (Admin use)
    public function show($id)
    {
        return response()->json(
            Maintenance::with(['plant', 'staff'])->findOrFail($id)
        );
    }

    // ✏️ UPDATE (Admin use)
    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $request->validate([
            'plant_id' => 'required|exists:plants,plant_id',
            'admin_staff_id' => 'required|exists:admin_staff,admin_staff_id',
            'task_type' => 'required|string',
            'scheduled_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $maintenance->update([
            'plant_id' => $request->plant_id,
            'admin_staff_id' => $request->admin_staff_id,
            'task_type' => $request->task_type,
            'scheduled_date' => $request->scheduled_date,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Maintenance updated successfully',
            'data' => $maintenance
        ]);
    }

    // ❌ DELETE (Admin use)
    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json([
            'message' => 'Maintenance deleted successfully'
        ]);
    }

    // 👨‍🔧 STAFF TASKS (FIXED)
    public function staffTasks()
{
    $staff = auth('admin')->user();

    if (!$staff) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    return Maintenance::with('plant')
        ->where('admin_staff_id', $staff->admin_staff_id)
        ->get();
}

    // 🔄 STAFF STATUS UPDATE (FIXED)
   public function updateStatus(Request $request, $id)
{
    $staff = auth('admin')->user();

    if (!$staff) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $maintenance = Maintenance::where('maintenance_id', $id)
        ->where('admin_staff_id', $staff->admin_staff_id)
        ->firstOrFail();

    $maintenance->update([
        'status' => $request->status
    ]);

    return response()->json([
        'message' => 'Status updated successfully',
        'data' => $maintenance
    ]);
}
}
// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Maintenance;

// class MaintenanceController extends Controller
// {
//     // 📥 GET ALL (with relations)
//     public function index()
//     {
//         return response()->json(
//             Maintenance::with(['plant', 'staff'])->get()
//         );
//     }

//     // ➕ CREATE
//     public function store(Request $request)
//     {
//         $request->validate([
//             'plant_id' => 'required|exists:plants,plant_id',
//             'admin_staff_id' => 'required|exists:admin_staff,admin_staff_id',
//             'task_type' => 'required|string',
//             'scheduled_date' => 'required|date',
//             'status' => 'required|string',
//         ]);

//         $maintenance = Maintenance::create([
//             'plant_id' => $request->plant_id,
//             'admin_staff_id' => $request->admin_staff_id,
//             'task_type' => $request->task_type,
//             'scheduled_date' => $request->scheduled_date,
//             'status' => $request->status,
//         ]);

//         return response()->json([
//             'message' => 'Maintenance created successfully',
//             'data' => $maintenance
//         ], 201);
//     }

//     // 📄 SINGLE
//     public function show($id)
//     {
//         return response()->json(
//             Maintenance::with(['plant', 'staff'])->findOrFail($id)
//         );
//     }

//     // ✏️ UPDATE
//     public function update(Request $request, $id)
//     {
//         $maintenance = Maintenance::findOrFail($id);

//         $request->validate([
//             'plant_id' => 'required|exists:plants,plant_id',
//             'admin_staff_id' => 'required|exists:admin_staff,admin_staff_id',
//             'task_type' => 'required|string',
//             'scheduled_date' => 'required|date',
//             'status' => 'required|string',
//         ]);

//         $maintenance->update([
//             'plant_id' => $request->plant_id,
//             'admin_staff_id' => $request->admin_staff_id,
//             'task_type' => $request->task_type,
//             'scheduled_date' => $request->scheduled_date,
//             'status' => $request->status,
//         ]);

//         return response()->json([
//             'message' => 'Maintenance updated successfully',
//             'data' => $maintenance
//         ]);
//     }

//     // ❌ DELETE
//     public function destroy($id)
//     {
//         $maintenance = Maintenance::findOrFail($id);
//         $maintenance->delete();

//         return response()->json([
//             'message' => 'Maintenance deleted successfully'
//         ]);
//     }


//     public function staffTasks()
// {
//     $staffId = Auth::user()->admin_staff_id;

//     return Maintenance::with('plant')
//         ->where('admin_staff_id', $staffId)
//         ->get();
// }

    

// public function updateStatus(Request $request, $id)
// {
//     $maintenance = Maintenance::findOrFail($id);

//     $maintenance->update([
//         'status' => $request->status
//     ]);

//     return response()->json([
//         'message' => 'Status updated successfully'
//     ]);
// }

// }