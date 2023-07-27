<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;


    protected $fillable = [
        'poster_id',
        'description',
        'src',
        'hashtags',
        'categories',
        'mentions',
        'poster_popularity_index',
        'poster_video_priority',
        'video_manual_boost_constant',
        'video_popularity'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
