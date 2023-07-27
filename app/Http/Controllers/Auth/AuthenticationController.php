<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        DB::beginTransaction();
        try {
            // validate request
            $registerRequest->validated();
            // data to insert to table
            $data = [
                'email' => $registerRequest->email,
                'username' => $registerRequest->username,
                'password' => Hash::make($registerRequest->password)
            ];
            // check if image is passed and add to data
            if ($registerRequest->hasFile('profile_photo')) {
                $registerRequest->validate([
                    'profile_photo' => 'mimes:jpeg,png,jpg'
                ]);
                $imagePath = 'public/images/profile_photo';
                $image = $registerRequest->file('profile_photo');
                $image_name = $image->getClientOriginalName();
                $path = $registerRequest->file('profile_photo')->storeAs($imagePath, rand(0, 50) . $image_name);

                $data['profile_photo'] = $path;
            }

            $user = User::create($data);
            $token = $user->createToken('triads')->plainTextToken;
            if ($user) {
                Profile::create(['user_id' => $user->id]);
                DB::commit();
                return response([
                    'user' => $user,
                    'token' => $token
                ], 201);
            } else {
                return response([
                    'message' => 'Error creating account'
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $loginRequest)
    {
        try {
            $loginRequest->validated();
            // check user
            $user = User::whereUsername($loginRequest->username)->first();
            if (!$user || !Hash::check($loginRequest->password, $user->password)) {
                return response([
                    'message' => 'Invalid Credentials'
                ], 400);
            }
            $token = $user->createToken('triads')->plainTextToken;
            return response([
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
