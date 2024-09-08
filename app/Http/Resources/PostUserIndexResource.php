<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostUserIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->whenLoaded("user");
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            // "user" => $this->user,
            // "user" => User::where("id", $this->user_id)->get()->value("name")
            "user" => new UserNameResource($this->user)
        ];
    }
}
