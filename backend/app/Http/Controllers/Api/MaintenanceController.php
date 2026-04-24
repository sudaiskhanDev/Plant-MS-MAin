<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    public function index()
    {
        return response()->json(Maintenance::with('plant')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plant_id' => 'required|exists:plants,plant_id',
            'task_type' => 'required|in:watering,pruning,fertilization',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $maintenance = Maintenance::create($request->all());

        return response()->json([
            'message' => 'Maintenance created',
            'data' => $maintenance
        ], 201);
    }

    public function show($id)
    {
        $data = Maintenance::with('plant')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Maintenance::find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data->update($request->all());

        return response()->json([
            'message' => 'Updated',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Maintenance::find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Deleted']);
    }
}