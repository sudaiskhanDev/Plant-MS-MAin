<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;

class MaintenanceController extends Controller
{
    public function index()
    {
        return response()->json(Maintenance::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'plant_id' => 'required',
            'task_type' => 'required',
            'scheduled_date' => 'required|date',
            'status' => 'required'
        ]);

        $maintenance = Maintenance::create($request->all());

        return response()->json($maintenance, 201);
    }

    public function show($id)
    {
        return response()->json(Maintenance::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $request->validate([
            'plant_id' => 'required',
            'task_type' => 'required',
            'scheduled_date' => 'required|date',
            'status' => 'required'
        ]);

        $maintenance->update($request->all());

        return response()->json($maintenance);
    }

    public function destroy($id)
    {
        Maintenance::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}