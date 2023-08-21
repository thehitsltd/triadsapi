<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikeVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id'
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
