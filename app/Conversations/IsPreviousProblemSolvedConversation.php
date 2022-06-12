<?php

namespace App\Conversations;

use App\BotMan\QuestionWrapperFactory;
use App\Enums\QuestionType;
use App\Enums\YesNoEnum;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class IsPreviousProblemSolvedConversation extends Conversation
{
    protected QuestionWrapperFactory $questionWrapperFactory;

    public function __construct()
    {
        $this->questionWrapperFactory = new QuestionWrapperFactory();
    }

    public function run()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::IS_PREVIOUS_DIFFICULTIES_SOLVED());
        $this->ask(
            Question::create($questionWrapper->getQuestionText())
                ->fallback('Unable to ask question')
                ->callbackId('ask_is_previous_difficulties_solved')
                ->addButtons([
                    Button::create('Да')->value(YesNoEnum::YES),
                    Button::create('Нет')->value(YesNoEnum::NO),
                ]),
            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);
                    $isSolved = YesNoEnum::fromValue($answer->getValue());
                    if ($isSolved->is(YesNoEnum::YES())) {
                        $this->askTheSolution();
                    }else{
                        $this->getBot()->startConversation(new TroubleShuttingConversation());
                    }
                } else {
                    $this->repeat();
                }
            }
        );
    }

    public function askTheSolution()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::HOW_YOU_SOLVE_PROBLEM());
        $this->ask(
            Question::create($questionWrapper->getQuestionText())
                ->fallback('Unable to ask question')
                ->callbackId('ask_how_you_solve_problem'),
            function (Answer $answer) use ($questionWrapper) {
                $questionWrapper->handleBotManAnswer($answer);
                $this->say('Это здорово!');
            }
        );
    }
}
