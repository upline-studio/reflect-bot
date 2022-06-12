<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function chat() {
        return $this->belongsTo(Chat::class);
    }
}
