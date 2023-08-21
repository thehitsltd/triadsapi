<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'profile_photo' => $this->profile_photo == null ? 'https://ui-avatars.com/api/?name=' . $this->username :
                getenv('APP_URL') . 'storage' .  str_replace('public', '', $this->profile_photo),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
