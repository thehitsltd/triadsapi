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
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{
    public $bucket;
    public $client;
    public $authToken;

    public function __construct()
    {
        $this->client = Http::withBasicAuth('00582ea9563c5e60000000002', 'K005gLZt0Sqn4Z1mtT/LN8edvfcsBmI')
            ->get('https://api.backblazeb2.com/b2api/v3/b2_authorize_account');
        $this->authToken = $this->client['authorizationToken'];
        //dd($this->client['apiInfo']);
    }

    public function get_upload_url()
    {
        $req = Http::withHeaders(['Authorization' => $this->authToken])
            ->get('https://api005.backblazeb2.com/b2api/v2/b2_get_upload_url?bucketId=' . getenv('BUCKET_ID'));
        return [
            'authToken' => $req['authorizationToken'],
            'uploadUrl' => $req['uploadUrl'],
        ];
    }

    public function register(RegisterRequest $registerRequest)
    {
        DB::beginTransaction();
        try {
            $uploadUrl = $this->get_upload_url();
            $url = $uploadUrl['uploadUrl'];
            $authT = $uploadUrl['authToken'];
            //dd($uploadUrl);
            // validate request
            //$registerRequest->validated();
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
                //$file = $registerRequest->file('profile_photo');


                $imagePath = 'public/images/profile_photo';
                $image = $registerRequest->file('profile_photo');
                $image_name = $image->getClientOriginalName();
                $path = $registerRequest->file('profile_photo')->storeAs($imagePath, rand(0, 50) . $image_name);
                //$originalFileName = $file->getClientOriginalName();
                //$filePath = $file->getRealPath();

                // Calculate SHA1 checksum of the file's content
                //$sha1Checksum = sha1_file($filePath);

                // Sanitize and URL-encode the original filename
                //$encodedFileName = urlencode($originalFileName);
                //dd($encodedFileName);

                //$fileSize = $file->getSize();

                // Calculate the total Content-Length including checksum
                //$totalContentLength = $fileSize + 40;
                /*$send = Http::withHeaders([
                    'Authorization' => $authT,
                    'X-Bz-File-Name' => $encodedFileName,
                    'Content-Type' => $file->getMimeType(),
                    'Content-Length' => $totalContentLength,
                    'X-Bz-Content-Sha1' => 'do_not_verify',
                ])
                    ->post($url, [
                        'body' => $registerRequest->file('profile_photo')
                    ]);

                dd($send);
                die(); */

                $data['profile_photo'] = $path;

                //dd($data);
            }

            $user = User::create($data);
            $token = $user->createToken('triads')->plainTextToken;
            if ($user) {
                Profile::create(['user_id' => $user->id]);
                DB::commit();
                return response([
                    'message' => 'success',
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

    public function checkUsername(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required'
            ]);
            $username = User::whereUsername($request->username)->first();
            if ($username) {
                return response([
                    'message' => $request->username . ' ' . 'has been taken'
                ], 201);
            } else {
                return response([
                    'message' => $request->username . ' ' . ' is available'
                ], 200);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users'
            ]);
            $email = User::whereEmail($request->email)->first();

            if ($email) {
                return response([
                    'message' => $request->email . ' ' . 'has been taken'
                ], 201);
            } else {
                return response([
                    'message' => $request->email . ' ' . ' is available'
                ], 200);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
