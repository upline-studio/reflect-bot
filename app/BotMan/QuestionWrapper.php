<?php

namespace App\BotMan;

use App\Models\Question;
use App\Service\QuestionService;
use BotMan\BotMan\Messages\Incoming\Answer;

class QuestionWrapper
{
    private Question $question;
    private string $chosenVariant;

    public function __construct(Question $question, string $chosenVariant)
    {
        $this->question = $question;
        $this->chosenVariant = $chosenVariant;
    }

    public function getQuestionText(): string
    {
        return $this->chosenVariant;
    }

    public function handleBotManAnswer(Answer $answer, string $savingValue = null): \App\Models\Answer
    {
        return app(QuestionService::class)->registerAnswer(
            $answer->getMessage()->getExtras('chat')->first(),
            $this->question,
            $this->chosenVariant,
            $savingValue ?? $answer->getText()
        );
    }
}
