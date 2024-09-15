<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){   
        $user_id = request()->input("user_id");
        if (isset($user_id)){
            $user = User::find($user_id);
            if ($user->notifications->count() > 0){
                return NotificationResource::collection($user->notifications); 
            } else {
                return response()->json(["message" => "No Notifications"], 200);
            }
        }

        $notifications = Notification::all();
        if ($notifications->count() > 0){
            return NotificationResource::collection($notifications); 
        } else {
            return response()->json(["message" => "No Notifications"], 200);
        }
    }
    public function store(Request $request){
        $notification = Notification::create([
            "text" => $request->text,
            "post_id" => $request->post_id
        ]);
        return response()->json([
            "message" => "Notification Created Successfully",
            "data" => new NotificationResource($notification)
        ], 200);
    }
    public function show(Notification $notification){
        return new NotificationResource($notification);
    }
    public function update(Request $request, Notification $notification){
        $notification->update([
            "text" => $request->text,
            "post_id" => $request->post_id,
        ]);
        return response()->json([
            "message" => "Notification Updated Successfully",
            "data" => new NotificationResource($notification)
        ], 200); 
    }
    public function destroy(Notification $notification){
        $notification->delete();
        return response()->json([
            "message" => "Notification Deleted Successfully",
        ], 200); 
    }
}
