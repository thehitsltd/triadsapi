<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'room_name' => $this->room_name,
            'map' => $this->map,
            'challenge_type' => $this->challenge_type,
            'weapon_type' => $this->weapon_type,
            'entry_point' => $this->entry_point,
            'spectators' => $this->spectators,
            'room_password' => $this->room_password,
            'is_private' => $this->is_private,
            'created_at' => $this->created_at,
            'creator' => new ProfileResource($this->user),
        ];
    }
}
