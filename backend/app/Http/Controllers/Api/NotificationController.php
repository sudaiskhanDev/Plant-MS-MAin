<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(Notification::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin_staff_id' => 'required',
            'message' => 'required',
            'type' => 'required',
            'date' => 'required|date'
        ]);

        $notification = Notification::create($request->all());

        return response()->json($notification, 201);
    }

    public function show($id)
    {
        return response()->json(Notification::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        $request->validate([
            'admin_staff_id' => 'required',
            'message' => 'required',
            'type' => 'required',
            'date' => 'required|date'
        ]);

        $notification->update($request->all());

        return response()->json($notification);
    }

    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}