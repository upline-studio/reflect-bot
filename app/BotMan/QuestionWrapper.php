<?php

namespace App\BotMan;

use App\Models\Answer as AnswerModel;
use App\Models\Question;
use App\Service\Chat\ChatService;
use App\Service\QuestionService;
use BotMan\BotMan\BotMan;
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

    public function getLatestAnswer(BotMan $botMan): ?AnswerModel
    {
        $chat = app(ChatService::class)->getChatFromBotMan($botMan);
        return app(QuestionService::class)->getLatestAnswer(
            $chat,
            $this->question->question_type
        );
    }

    public function handleBotManAnswer(Answer $answer, string $savingValue = null): AnswerModel
    {
        return app(QuestionService::class)->registerAnswer(
            app(ChatService::class)->getChatFromAnswer($answer),
            $this->question,
            $this->chosenVariant,
            $savingValue ?? $answer->getText()
        );
    }
}
