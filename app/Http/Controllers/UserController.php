<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::get();
        if ($users->count() > 0){
            return UserResource::collection($users); 
        } else {
            return response()->json(["message" => "No Users"], 200);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:100",
            "email" => "required|string|max:255",
            // "password" => "required|string",
        ]);
        
        if($validator->fails()){
            return response()->json([
                "message" => "All fields are mandetory",
                "error" => $validator->messages()
            ], 422);
        }
        $user = User::create([
            "name" => $request->title,
            "email" => $request->description,
            "password" => $request->text,
        ]);
        return response()->json([
            "message" => "User Created Successfully",
            "data" => new UserResource($user)
        ], 200);
    }
    public function show(User $user){
        return new UserResource($user);
    }
    public function update(Request $request, User $user){
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:100",
            "email" => "required|string|max:255",
            "password" => "required|string",
        ]);
        
        if($validator->fails()){
            return response()->json([
                "message" => "All fields are mandetory",
                "error" => $validator->messages()
            ], 422);
        }
        $user->update([
            "name" => $request->title,
            "email" => $request->description,
            "password" => $request->text,
        ]);
        return response()->json([
            "message" => "User Updated Successfully",
            "data" => new UserResource($user)
        ], 200); 
    }
    public function destroy(User $user){
        $user->delete();
        return response()->json([
            "message" => "User Deleted Successfully",
        ], 200); 
    }
}
