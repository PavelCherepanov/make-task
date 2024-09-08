<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostUserIndexResource;
use App\Http\Resources\PostUserShowResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
  

    public function index(){
        $order = request()->input("order");
        if ($order == "oldest"){
            $posts = Post::orderBy('created_at', 'ASC')->paginate(15);
            if ($posts->count() > 0){
                return PostUserIndexResource::collection($posts); 
            } else {
                return response()->json(["message" => "No Posts"], 200);
            }
        }
        $posts = Post::orderBy('created_at', 'DESC')->paginate(15);
        if ($posts->count() > 0){
            return PostUserIndexResource::collection($posts); 
        }else{
            return response()->json(["message" => "No Posts"], 200);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "title" => "required|string|max:100",
            "description" => "required|string|max:255",
            "text" => "required|string",
            "user_id" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "All fields are mandetory",
                "error" => $validator->messages()
            ], 422);
        }
        $post = Post::create([
            "title" => $request->title,
            "description" => $request->description,
            "text" => $request->text,
            "user_id" => $request->user_id
        ]);
        return response()->json([
            "message" => "Post Created Successfully",
            "data" => new PostResource($post)
        ], 200);
    }
    public function show(Post $post){
        return new PostUserShowResource($post);
    }
    public function update(Request $request, Post $post){
       
        $validator = Validator::make($request->all(), [
            "title" => "required|string|max:100",
            "description" => "required|string|max:255",
            "text" => "required|string",
            "user_id" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "All fields are mandetory",
                "error" => $validator->messages()
            ], 422);
        }
        $post->update([
            "title" => $request->title,
            "description" => $request->description,
            "text" => $request->text,
        ]);
        return response()->json([
            "message" => "Post Updated Successfully",
            "data" => new PostResource($post)
        ], 200); 
    }
    public function destroy(Post $post){
        $post->delete();
        return response()->json([
            "message" => "Post Deleted Successfully",
        ], 200); 
    }
}