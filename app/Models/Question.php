<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Question extends Model
{
    protected $casts = [
        'question_type' => QuestionType::class,
        'variants' => 'array'
    ];

    public function getRandomVariant()
    {
        return Arr::random($this->variants, 1)[0];
    }
}
