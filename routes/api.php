<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Triad\PostController;
use App\Http\Controllers\Triad\RoomController;
use App\Http\Controllers\Triad\UserRelationShipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('videos', [PostController::class, 'index']);
    Route::post('video/upload', [PostController::class, 'store']);
    Route::post('user/follow/{user_id}', [UserRelationShipController::class, 'followandunfollow']);
    Route::get('video/{video_id}', [PostController::class, 'view']);
    Route::get('video/like/{video_id}', [PostController::class, 'like']);
    Route::post('create/challenge/room', [RoomController::class, 'store']);
    Route::get('rooms', [RoomController::class, 'index']);
});

Route::post('register', [AuthenticationController::class, 'register'])->middleware('res');
Route::post('login', [AuthenticationController::class, 'login'])->middleware('res');
