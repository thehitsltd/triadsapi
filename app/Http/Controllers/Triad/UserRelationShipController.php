<?php

namespace App\Http\Controllers\Triad;

use App\Http\Controllers\Controller;
use App\Models\UserRelationship;
use Illuminate\Http\Request;

class UserRelationShipController extends Controller
{
    public function followandunfollow(int $userId)
    {
        try {
            $rel = UserRelationship::whereUserId(auth()->id())
                ->whereRelatedUserId($userId)->first();
            //checking if empty and follow
            if (empty($rel)) {
                UserRelationship::create([
                    'user_id' => auth()->id(),
                    'related_user_id' => $userId
                ]);
                return response([
                    'status' => true
                ]);
            } else {
                $rel = UserRelationship::whereUserId(auth()->id())
                    ->whereRelatedUserId($userId)->delete();
                return response([
                    'status' => true
                ]);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
