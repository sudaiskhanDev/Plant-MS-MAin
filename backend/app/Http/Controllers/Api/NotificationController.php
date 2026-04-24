<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(
            Notification::with('adminStaff')->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_staff_id' => 'required|exists:admin_staff,admin_staff_id',
            'message' => 'required|string',
            'type' => 'required|in:stock,maintenance,order',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $notification = Notification::create([
            'admin_staff_id' => $request->admin_staff_id,
            'message' => $request->message,
            'type' => $request->type,
            'date' => now(),
        ]);

        return response()->json([
            'message' => 'Notification created',
            'data' => $notification
        ], 201);
    }

    public function show($id)
    {
        $data = Notification::with('adminStaff')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Notification::find($id);

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
        $data = Notification::find($id);

        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Deleted']);
    }
}