<?php

namespace App\Http\Controllers\Triad;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        try {
            $user = User::whereId(auth()->id())->with('profile')->first();
            $user = new UserResource($user);
            return response([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::whereId($id)->with('profile')->first();
            $user = new UserResource($user);
            return response([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $uData = [
                'username' => $request->username
            ];
            if ($request->hasFile('profile_photo')) {
                $request->validate([
                    'profile_photo' => 'mimes:jpeg,png,jpg'
                ]);
                //$file = $request->file('profile_photo');
                $imagePath = 'public/images/profile_photo';
                $image = $request->file('profile_photo');
                $image_name = $image->getClientOriginalName();
                $path = $request->file('profile_photo')->storeAs($imagePath, rand(0, 50) . $image_name);
                $uData['profile_photo'] = $path;
            }
            $user = User::whereId(auth()->id())->update($uData);
            if ($user) {
                $data = [
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'website' => $request->website,
                    'bio' => $request->bio,
                ];
                Profile::whereUserId(auth()->id())->update($data);
                DB::commit();
                return response([
                    'message' => 'success'
                ], 201);
            } else {
                return response([
                    'message' => 'Error'
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
}
