<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostUserShowResource extends JsonResource
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
            "text" => $this->text,
            "user" => new UserNameEmailResource($this->user)
        ];
    }
}
