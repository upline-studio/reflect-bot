<?php

namespace App\Conversations;

use App\BotMan\EnumQuestion;
use App\BotMan\QuestionWrapperFactory;
use App\Commands\StartNewChallengeCommand;
use App\Enums\ChallengeAttitude;
use App\Enums\QuestionType;
use App\Enums\YesNoEnum;
use App\Models\Challenge;
use App\Service\ChallengeData;
use App\Service\ChallengeService;
use App\Service\Chat\ChatService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Str;

class ChallengeReflectionConversation extends Conversation
{
    protected Challenge $challenge;
    protected QuestionWrapperFactory $questionWrapperFactory;
    protected ChallengeService $challengeService;
    protected ChatService $chatService;
    protected ?Conversation $nextConversation;

    public function __construct(Challenge $challenge, ?Conversation $nextConversation = null)
    {
        $this->challenge = $challenge;
        $this->questionWrapperFactory = new QuestionWrapperFactory();
        $this->challengeService = new ChallengeService();
        $this->chatService = new ChatService();
        $this->nextConversation = $nextConversation;
    }

    public function run()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::CHALLENGE_DO_YOU_USE_TECHNIC());
        $this->ask(
            EnumQuestion::create(Str::replace('%challenge_name%', $this->challenge->name, $questionWrapper->getQuestionText()))
                ->fallback('Unable to ask question')
                ->callbackId(QuestionType::CHALLENGE_DO_YOU_USE_TECHNIC)
                ->addButtonsFromEnum(YesNoEnum::class),

            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);
                    $used = YesNoEnum::fromValue($answer->getValue());
                    if ($used->is(YesNoEnum::YES())) {
                        $this->askResult();
                    } else {
                        $this->askNextDay();
                    }
                } else {
                    $this->repeat();
                }
            }
        );
    }

    public function askResult()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::CHALLENGE_DO_YOU_LIKE_IT());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->fallback('Unable to ask question')
                ->callbackId(QuestionType::CHALLENGE_DO_YOU_LIKE_IT)
                ->addButtonsFromEnum(ChallengeAttitude::class),

            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);
                    $attitude = ChallengeAttitude::fromValue($answer->getValue());
                    if ($attitude->is(ChallengeAttitude::DONT_DECIDE)) {
                        $this->say('Хорошо, тогда спрошу завтра.');
                        return;
                    }
                    $this->askTellWhatYouLike($attitude);
                } else {
                    $this->repeat();
                }
            }
        );
    }

    public function askNextDay()
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::CHALLENGE_TRY_TOMORROW());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->fallback('Unable to ask question')
                ->callbackId(QuestionType::CHALLENGE_TRY_TOMORROW)
                ->addButtonsFromEnum(YesNoEnum::class),

            function (Answer $answer) use ($questionWrapper) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);
                    $used = YesNoEnum::fromValue($answer->getValue());
                    if ($used->is(YesNoEnum::NO())) {
                        $this->say('Хорошо, перейдем к следующей технике.');
                        $this->challengeService->skipChallenge(
                            $this->chatService->getChatFromAnswer($answer),
                            $this->challenge
                        );

                        StartNewChallengeCommand::make()->run($this->getBot());
                    } else {
                        $this->say('Хорошо');
                        if ($this->nextConversation) {
                            $this->getBot()->startConversation($this->nextConversation);
                        }
                    }
                } else {
                    $this->repeat();
                }
            }
        );
    }

    public function askTellWhatYouLike(ChallengeAttitude $attitude)
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::CHALLENGE_TELL_WHAT_YOU_LIKE());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->fallback('Unable to ask question')
                ->callbackId(QuestionType::CHALLENGE_TELL_WHAT_YOU_LIKE),

            function (Answer $answer) use ($questionWrapper, $attitude) {
                $questionWrapper->handleBotManAnswer($answer);
                $comment = $answer->getText();
                $this->askWillYouUse($attitude, $comment);
            }
        );
    }

    private function askWillYouUse(ChallengeAttitude $attitude, string $comment)
    {
        $questionWrapper = $this->questionWrapperFactory->getQuestionWrapper(QuestionType::CHALLENGE_WILL_YOU_USE());
        $this->ask(
            EnumQuestion::create($questionWrapper->getQuestionText())
                ->fallback('Unable to ask question')
                ->callbackId(QuestionType::CHALLENGE_WILL_YOU_USE)
                ->addButtonsFromEnum(YesNoEnum::class),

            function (Answer $answer) use ($questionWrapper, $attitude, $comment) {
                if ($answer->isInteractiveMessageReply()) {
                    $questionWrapper->handleBotManAnswer($answer);
                    $willUse = YesNoEnum::fromValue($answer->getValue());
                    $this->challengeService->fillChallengeResult(
                        new ChallengeData(
                            $this->chatService->getChatFromAnswer($answer),
                            $this->challenge,
                            $comment,
                            $attitude,
                            $willUse->is(YesNoEnum::YES)
                        )
                    );
                    $this->say('Спасибо за ваши ответы!');
                    StartNewChallengeCommand::make()->run($this->getBot());
                    if ($this->nextConversation) {
                        $this->getBot()->startConversation($this->nextConversation);
                    }
                } else {
                    $this->repeat();
                }
            }
        );
    }
}
