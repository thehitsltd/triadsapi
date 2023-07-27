<?php

namespace App\Http\Controllers\Triad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Triads\VideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        try {
            $videos = Video::with('user')->latest()->get();
            return response([
                'message' => 'success',
                'videos' => $videos
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function view($videoId)
    {
        try {
            $video = Video::with('user')->whereId($videoId)->first();
            if ($video) {
                Video::with('user')->whereId($videoId)->update(['video_popularity', '0.01']);
                return response([
                    'message' => 'success',
                    'video' => $video
                ]);
            } else {
                return response([
                    'message' => 'Not found'
                ], 400);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function like($videoId)
    {
        try {
            $video = Video::with('user')->whereId($videoId)->first();
            if ($video) {
                Video::with('user')->whereId($videoId)->update(['video_popularity', '0.01']);
                return response([
                    'message' => 'success',
                ]);
            } else {
                return response([
                    'message' => 'Not found'
                ], 400);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(VideoRequest $videoRequest)
    {
        try {
            //validate data
            $videoRequest->validated();
            $data = [
                'description' => $videoRequest->description,
                'hashtags' => $videoRequest->hashtags,
                'categories' => $videoRequest->categories,
                'mentions' => $videoRequest->mentions,
                'poster_popularity_index' => 0.0,
                'poster_video_priority' => 0.0,
                'video_manual_boost_constant' => 0.0,
                'video_popularity' => 0.0
            ];
            $videoPath = 'public/videos';
            $video = $videoRequest->file('src');
            $video_name = $video->getClientOriginalName();
            $path = $videoRequest->file('profile_photo')->storeAs($videoPath, auth()->id() . '/' . uniqid() . uniqid() . $video_name);

            $data['src'] = $path;

            $save_video = auth()->user()->videos()->create($data);
            if ($save_video) {
                return response([
                    'message' => 'success',
                    'video' => $save_video
                ], 201);
            } else {
                return response([
                    'message' => 'error',
                ], 500);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
