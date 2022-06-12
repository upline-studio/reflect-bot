<?php

namespace App\Conversations;

use App\BotMan\QuestionWrapperFactory;
use App\Enums\QuestionType;
use App\Models\Challenge;
use App\Models\Chat;
use App\Service\QuestionService;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ChallengeConversation extends Conversation
{
    /**
     * First question
     */

    protected string $positiveAnswer = 'Да';

    public function run()
    {
        $this->executeWantChallengeQuestion();
    }

    private function executeWantChallengeQuestion() {
        $question = $this->getWantChallengeQuestion();
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper(QuestionType::CHALLENGE());

        $this->ask($question, function (Answer $answer) use ($questionWrapper) {
            if ($answer->isInteractiveMessageReply()) {
                $answerData = $questionWrapper->handleBotManAnswer($answer);
                $value = $answer->getValue();

                if ($value !== $this->positiveAnswer) {
                    $this->say('Хорошо. Тогда, в следующий раз!');
                    return;
                }

                $challenge = $this->getAvailableChallenge($answerData->chat);

                if (empty($challenge)) {
                    $this->say('Ты прошел все челленджи!');
                    return;
                }

                $this->say('Челлендж: ' . $challenge->name);
                $this->say($challenge->description);

                $this->executeAcceptChallengeQuestion($challenge);
            }
        });
    }

    private function executeAcceptChallengeQuestion(Challenge $challenge) {
        $question = $this->getAcceptChallengeQuestion();
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper(QuestionType::CHALLENGE());

        $this->ask($question, function (Answer $answer) use ($questionWrapper, $challenge) {
            $answerData = $questionWrapper->handleBotManAnswer($answer);
            $value = $answer->getValue();
            $questionWrapper->handleBotManAnswer($answer);

            if ($value !== $this->positiveAnswer) {
                $this->say('Хорошо. Тогда, в следующий раз!');
                return;
            }

            $answerData->chat->challenge_id = $challenge->id;
            $answerData->chat->save();

            $this->say('Отлично! Пробуй учиться этим методом!');
        });
    }

    private function getAvailableChallenge(Chat $chat): ?Challenge {
        $completedChallenges = $chat->challenges->pluck('challenge_id')->all();
        return Challenge::whereNotIn('id', $completedChallenges)->orderBy('sort_order')->first();
    }

    private function getWantChallengeQuestion(): Question
    {
        $questionService = app(QuestionService::class);
        return Question::create(
            $questionService
                ->getRandomQuestion(QuestionType::CHALLENGE())
                ->getRandomVariant()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_want_challenge')
            ->addButtons([
                Button::create('👍 Да')->value($this->positiveAnswer),
                Button::create('❌ Нет')->value('Нет'),
            ]);
    }

    private function getAcceptChallengeQuestion(): Question {
        $questionService = app(QuestionService::class);
        return Question::create(
            $questionService
                ->getRandomQuestion(QuestionType::ACCEPT_CHALLENGE())
                ->getRandomVariant()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_accept_challenge')
            ->addButtons([
                Button::create('👍 Да')->value($this->positiveAnswer),
                Button::create('❌ Нет')->value('Нет'),
            ]);
    }
}
