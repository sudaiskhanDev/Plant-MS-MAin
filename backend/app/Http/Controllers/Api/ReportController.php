<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(Report::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'generated_date' => 'required|date'
        ]);

        $report = Report::create($request->all());

        return response()->json($report, 201);
    }

    public function show($id)
    {
        return response()->json(Report::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $request->validate([
            'type' => 'required',
            'generated_date' => 'required|date'
        ]);

        $report->update($request->all());

        return response()->json($report);
    }

    public function destroy($id)
    {
        Report::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}