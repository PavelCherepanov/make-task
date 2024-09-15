<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostUserIndexResource;
use App\Http\Resources\PostUserShowResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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

        // Отправка уведомлений всем пользователям о создании нового поста
        $notification_user = User::find($post->user_id);
        $notification_text = "У пользователя " . $notification_user->name .
         " вышел новый пост. Обязательно прочитайте и напишите комментарий на почту ". $notification_user->email;
        $notification = Notification::create([
            "text"=> $notification_text, 
            "post_id" => $post->id
        ]);
        $users = User::find(User::pluck('id')->toArray()); 
        $notification->users()->attach($users);
        # Отправка по почте уведомлений   
        $emails = $notification->users()->get()->pluck("email")->toArray();
        foreach ($emails as $email) {
            Mail::raw($notification_text, fn ($mail) => $mail->to($email));
        }
    
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