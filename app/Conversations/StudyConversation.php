<?php

namespace App\Conversations;

use App\BotMan\QuestionWrapperFactory;
use App\Enums\QuestionType;
use App\Models\Chat;
use App\Service\Chat\ChatService;
use App\Service\QuestionService;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class StudyConversation extends Conversation
{
    /**
     * First question
     */
    public function run()
    {
        $this->executeAppraisalQuestion();
    }

    private function executeAppraisalQuestion()
    {
        $questionWrapper = app(QuestionWrapperFactory::class)
            ->getQuestionWrapper(QuestionType::HOW_YOUR_STUDY());

        $previousAnswer = $questionWrapper->getLatestAnswer($this->getBot());

        $question = $this->getAppraisalQuestion($questionWrapper->getQuestionText());

        $wasPreviousBad = $previousAnswer?->answer === QuestionType::BAD_EXPERIENCE;

        $this->ask($question, function (Answer $answer) use ($questionWrapper, $wasPreviousBad) {
            if ($answer->isInteractiveMessageReply()) {
                $questionWrapper->handleBotManAnswer($answer);
                $value = QuestionType::fromValue($answer->getValue());

                if ($wasPreviousBad && $value->is(QuestionType::BAD_EXPERIENCE)) {
                    $this->getBot()->startConversation(new TroubleShuttingConversation());
                } else {
                    $this->executeAppraisalInDepthQuestion($value);

                }

            }
        });
    }

    private function getAppraisalQuestion(string $text): Question
    {
        return Question::create($text)
            ->fallback('Unable to ask question')
            ->callbackId('ask_study_reflection')
            ->addButtons([
                Button::create('😒 что-то не очень')->value(QuestionType::BAD_EXPERIENCE),
                Button::create('😕 пойдет')->value(QuestionType::OK_EXPERIENCE),
                Button::create('😃 супер')->value(QuestionType::GOOD_EXPERIENCE),
            ]);
    }

    private function executeAppraisalInDepthQuestion(QuestionType $questionType)
    {
        $question = $this->getAppraisalInDepthQuestion($questionType);
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper($questionType);
        $this->ask($question, function (Answer $answer) use ($questionWrapper) {
            $questionWrapper->handleBotManAnswer($answer);
            $this->say('Спасибо за ответ!');


            $currentChallenge = app(ChatService::class)
                ->getChatFromAnswer($answer)
                ->currentChallenge;

            if ($currentChallenge) {
                $this->getBot()
                    ->startConversation(new ChallengeReflectionConversation($currentChallenge));
            }
        });

    }

    private function getAppraisalInDepthQuestion(QuestionType $questionType): Question
    {
        $questionService = app(QuestionService::class);

        return Question::create(
            $questionService
                ->getRandomQuestion($questionType)
                ->getRandomVariant()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_study_reflection');
    }
}
