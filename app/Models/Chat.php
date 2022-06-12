<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'chat_challenges');
    }

    public function currentChallenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }
}
