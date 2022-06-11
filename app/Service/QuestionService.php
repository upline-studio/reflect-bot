<?php

namespace App\Service;

use App\Enums\QuestionType;
use App\Models\Question;

class QuestionService
{
    public function getRandomQuestion(QuestionType $questionType): Question
    {
        return Question::where('question_type', $questionType->value)->inRandomOrder()->first();
    }
}
