<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'description' => $this->description,
            'src' => getenv('APP_URL') . 'storage' . str_replace('public', '', $this->src),
            // 'src' => 'https://rr5---sn-p5qlsn7s.googlevideo.com/videoplayback?expire=1692644829&ei=fWHjZPjkAd-T_9EPko630Ak&ip=181.214.94.154&id=o-ABCM7xr8nS-RdVqJKK1lgHnWCqjMs9HTTTa-ewet0DfE&itag=22&source=youtube&requiressl=yes&mh=3h&mm=31%2C29&mn=sn-p5qlsn7s%2Csn-p5qddn7d&ms=au%2Crdu&mv=m&mvi=5&pl=24&initcwndbps=1145000&spc=UWF9f7FR4bhf8-cquM-bm2idOWVOD9I&vprv=1&svpuc=1&mime=video%2Fmp4&cnr=14&ratebypass=yes&dur=2452.979&lmt=1692093029348134&mt=1692622883&fvip=3&fexp=24007246%2C51000011&beids=24350017&c=ANDROID&txp=5318224&sparams=expire%2Cei%2Cip%2Cid%2Citag%2Csource%2Crequiressl%2Cspc%2Cvprv%2Csvpuc%2Cmime%2Ccnr%2Cratebypass%2Cdur%2Clmt&sig=AOq0QJ8wRgIhALHfDZ276LNlKfC1ncEh8kijCNypzPAoHWOD96IlqK47AiEAmJdwWscrSJtLEyhiMdBQqtV2cTeO8UMdG9NDsBucKmQ%3D&lsparams=mh%2Cmm%2Cmn%2Cms%2Cmv%2Cmvi%2Cpl%2Cinitcwndbps&lsig=AG3C_xAwRgIhAMeU1J-TNhPr9qP-_c7vOWFYK0raed1aioXX_siSsoMrAiEAq6izlSlNS5kptBiuUyRG_FlD7zKyD4YO7zyjYuXCfY0%3D&title=Flutter%20Clean%20Architecture%20-%20Learn%20By%20A%20Project%20%7C%20Full%20Beginner%27s%20Tutorial',
            "hashtags" => $this->hashtags,
            "categories" => $this->categories,
            "mentions" =>  $this->mentions,
            "poster_popularity_index" => $this->poster_popularity_index,
            "poster_video_priority" => $this->poster_video_priority,
            "video_manual_boost_constant" => $this->video_manual_boost_constant,
            "video_popularity" => $this->video_popularity,
            "created_at" => $this->created_at->diffForHumans(),
            "updated_at" => $this->updated_at,
            "user" => new UserResource($this->user),
            "likes" => $this->likes->pluck('user_id'),
            'like_count' => $this->likes->count(),
            'comments' => CommentResource::collection($this->comments),
            'comment_count' => $this->comments->count()
        ];
    }
}
