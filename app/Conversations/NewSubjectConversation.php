<?php

namespace App\Conversations;

use App\BotMan\QuestionWrapperFactory;
use App\Commands\ChatCommand;
use App\Enums\QuestionType;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class NewSubjectConversation extends Conversation
{
    protected ?ChatCommand $next;

    public function __construct(ChatCommand $next = null)
    {
        $this->next = $next;
    }

    /**
     * First question
     */
    public function run()
    {
        $this->askSubject();
    }

    public function askSubject()
    {
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper(QuestionType::WHAT_IS_SUBJECT());

        $question = Question::create($questionWrapper->getQuestionText())
            ->fallback('Unable to ask question')
            ->callbackId('ask_subject');

        return $this->ask($question, function (Answer $answer) use ($questionWrapper) {
            $questionWrapper->handleBotManAnswer($answer);
            $this->askGoal();
        });
    }

    public function askGoal()
    {
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper(QuestionType::WHAT_IS_GOAL());

        $question = Question::create(
            $questionWrapper
                ->getQuestionText()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_goal');

        return $this->ask($question, function (Answer $answer) use ($questionWrapper) {
            $questionWrapper->handleBotManAnswer($answer);
            $this->say('Спасибо за твои ответы!');
            if ($this->next) {
                ($this->next)($this->getBot());
            }
        });
    }
}
