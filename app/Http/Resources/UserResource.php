<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'bio' => $this->bio ?? '',
            'avatar' => $this->avatar ? env('APP_URL')."/public/".$this->avatar : env('APP_URL')."/public/profile/default.jpg",
            'role' => $this->role ?? '',
            'provider_name' => $this->provider_name ?? '',
            'provider_id' => $this->provider_id ?? '',
            'email_verified_at' => $this->email_verified_at ? $this->email_verified_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
