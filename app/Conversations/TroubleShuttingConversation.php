<?php

namespace App\Conversations;

use App\BotMan\EnumQuestion;
use App\BotMan\QuestionWrapperFactory;
use App\Enums\QuestionType;
use App\Enums\WhatsGoingOnAnswers;
use App\Enums\YesNoEnum;
use App\Service\Chat\ChatService;
use App\Service\QuestionService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class TroubleShuttingConversation extends Conversation
{
    protected QuestionWrapperFactory $questionWrapperFactory;
    protected ChatService $chatService;
    protected QuestionService $questionService;

    public function __construct()
    {
        $this->questionWrapperFactory = new QuestionWrapperFactory();
        $this->chatService = app(ChatService::class);
        $this->questionService = app(QuestionService::class);
    }

    public function run()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::TRY_TO_FIGURE_OUT());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->addButtonsFromEnum(YesNoEnum::class),
            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);

                    if ($answer->getValue() === YesNoEnum::YES) {
                        $this->askWhatsGoingOn();
                    } else {
                        $this->say('Хорошо. В любой момент вы можете вернуться к это теме и попробовать в ней разобраться.');
                    }
                } else {
                    $this->repeat();
                }
            });
    }

    public function askWhatsGoingOn()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::WHATS_GOING_ON());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->addButtonsFromEnum(WhatsGoingOnAnswers::class),
            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);

                    switch ($answer->getValue()) {
                        case WhatsGoingOnAnswers::I_AM_CONFUSED:
                            $this->say('Найдите место где все было хорошо, учеба шла гладко. И посмотрите в самом конце этого места что-то, что вы не поняли. Обычно путаница начинается именно после этого момента.');
                            break;
                        case WhatsGoingOnAnswers::I_AM_BORED:
                        case WhatsGoingOnAnswers::DONT_NOW_WHY_I_AM_STUDYING:
                            $this->say('Посмотрите на вашу цель. Зачем вы изучаете предмет?');
                            $chat = $this->chatService->getChatFromAnswer($answer);
                            $goal = app(QuestionService::class)
                                ->getLatestAnswer(
                                    $chat,
                                    QuestionType::WHAT_IS_GOAL()
                                );
                            if ($goal) {
                                $this->say($goal->answer);
                            }
                            $this->askWhatYouHaveLearnt();
                            break;
                        case WhatsGoingOnAnswers::I_KNOW_EVERYTHING:
                            $this->askWhyDoYouLearn();
                            break;
                        case WhatsGoingOnAnswers::SOMETHING_ELSE:
                            $this->askWhatElse();
                            break;

                    }
                } else {
                    $this->repeat();
                }
            });
    }

    public function askWhatYouHaveLearnt()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::WHAT_HAVE_YOU_LEARNT_AFTER_START());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText()),
            function (Answer $answer) use ($questionWrapper) {
                $questionWrapper->handleBotManAnswer($answer);
                $this->say('Спасибо!');
            });
    }

    public function askWhyDoYouLearn()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::WHY_DO_YOU_LEARN());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText()),
            function (Answer $answer) use ($questionWrapper) {
                $questionWrapper->handleBotManAnswer($answer);
                $this->say('Спасибо!');
            });
    }

    public function askWhatElse()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::TELL_ABOUT_DIFFICULTIES());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText()),
            function (Answer $answer) use ($questionWrapper) {
                $questionWrapper->handleBotManAnswer($answer);
                $this->say('Спасибо!');

                $this->askSendToMentor($answer->getText());
            });
    }

    public function askSendToMentor(string $text)
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::SEND_TO_MENTOR());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->addButtonsFromEnum(YesNoEnum::class),
            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);
                    if ($answer->getValue() === YesNoEnum::YES) {
                        //TODO: send to mentor
                        $this->say('Спасибо, мы передадим вашу проблему наставнику!');
                    } else {
                        $this->say('Спасибо! Вы всегда можете попросить помощи у наставников и организаторов курса.');
                    }

                } else {
                    $this->repeat();
                }
            });
    }
}
