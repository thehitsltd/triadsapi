<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'room_name',
        'map',
        'challenge_type',
        'weapon_type',
        'entry_point',
        'spectators',
        'room_password'
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }
}
