<?php

namespace App\Http\Controllers\Triad;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Room;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function store(RoomRequest $roomRequest)
    {
        DB::beginTransaction();
        try {
            $roomRequest->validated();
            $data = [
                'creator_id' => auth()->id(),
                'room_name' => $roomRequest->room_name,
                'map' => $roomRequest->map,
                'challenge_type' => $roomRequest->challenge_type,
                'weapon_type' => $roomRequest->weapon_type,
                'entry_point' => $roomRequest->entry_point,
                'spectators' => $roomRequest->spectators
            ];
            if (!empty($roomRequest->room_password)) {
                $roomRequest->validate([
                    'room_password' => 'min:4',
                ]);
                $data['room_password'] = $roomRequest->room_password;
            }
            $room = Room::create($data);
            if ($room) {
                if (!empty($roomRequest->rule))
                    Rule::create([
                        'room_id' => auth()->id(),
                        'rule' => $roomRequest->rule
                    ]);
                DB::commit();
                return response([
                    'message' => 'success'
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $rooms = Room::with('rules')->latest()->get();
            return response([
                'rooms' => $rooms
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
