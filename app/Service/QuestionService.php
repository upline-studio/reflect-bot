<?php

namespace App\Service;

use App\Enums\QuestionType;
use App\Models\Answer;
use App\Models\Chat;
use App\Models\Question;

class QuestionService
{
    public function getRandomQuestion(QuestionType $questionType): Question
    {
        return Question::where('question_type', $questionType->value)->inRandomOrder()->first();
    }

    public function registerAnswer(Chat $chat, Question $question, string $variant, string $answerText): Answer
    {
        $answer = new Answer();
        $answer->question_id = $question->id;
        $answer->question_variant = $variant;
        $answer->answer = $answerText;
        $answer->chat_id = $chat->id;
        $answer->save();
        return $answer;
    }
}
