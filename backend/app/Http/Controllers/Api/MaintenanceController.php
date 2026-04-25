<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;

class MaintenanceController extends Controller
{
    // 📥 GET ALL (with relations)
    public function index()
    {
        return response()->json(
            Maintenance::with(['plant', 'staff'])->get()
        );
    }

    // ➕ CREATE
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

    // 📄 SINGLE
    public function show($id)
    {
        return response()->json(
            Maintenance::with(['plant', 'staff'])->findOrFail($id)
        );
    }

    // ✏️ UPDATE
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

    // ❌ DELETE
    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json([
            'message' => 'Maintenance deleted successfully'
        ]);
    }

    
}