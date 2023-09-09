<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeletedReelsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "description" => $this->description,
            "video_path" => env('APP_URL').'/public/'.$this->video_path,
            "likes" => $this->likes,
            "views" => $this->views,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "updated_at" => $this->updated_at->format('Y-m-d H:i:s'),
            "deleted_at" => $this->deleted_at->format('Y-m-d H:i:s'),

        ];
    }
}
