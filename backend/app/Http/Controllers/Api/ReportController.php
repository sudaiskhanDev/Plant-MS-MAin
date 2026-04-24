<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(
            Report::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $report = Report::create([
            'type' => $request->type,
            'generated_date' => now(),
        ]);

        return response()->json([
            'message' => 'Report created',
            'data' => $report
        ]);
    }

    public function show($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($report);
    }

    public function destroy($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $report->delete();

        return response()->json(['message' => 'Deleted']);
    }
}