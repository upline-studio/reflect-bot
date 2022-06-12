<?php

namespace App\Conversations;

use App\BotMan\QuestionWrapperFactory;
use App\Enums\QuestionType;
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

    private function executeAppraisalQuestion() {
        $question = $this->getAppraisalQuestion();
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper(QuestionType::HOW_YOUR_STUDY());

        $this->ask($question, function (Answer $answer) use ($questionWrapper) {
            if ($answer->isInteractiveMessageReply()) {
                $questionWrapper->handleBotManAnswer($answer);
                $value = QuestionType::fromValue($answer->getValue());
                $this->executeAppraisalInDepthQuestion($value);
            }
        });
    }

    private function getAppraisalQuestion(): Question
    {
        $questionService = app(QuestionService::class);
        return Question::create(
            $questionService
                ->getRandomQuestion(QuestionType::HOW_YOUR_STUDY())
                ->getRandomVariant()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_study_reflection')
            ->addButtons([
                Button::create('😒 что-то не очень')->value(QuestionType::BAD_EXPERIENCE),
                Button::create('😕 пойдет')->value(QuestionType::OK_EXPERIENCE),
                Button::create('😃 супер')->value(QuestionType::GOOD_EXPERIENCE),
            ]);
    }

    private function executeAppraisalInDepthQuestion(QuestionType $questionType) {
        $question = $this->getAppraisalInDepthQuestion($questionType);
        $questionWrapper = app(QuestionWrapperFactory::class)->getQuestionWrapper($questionType);
        $this->ask($question, function (Answer $answer) use ($questionWrapper) {
            $questionWrapper->handleBotManAnswer($answer);
            $this->say('Спасибо за ответ!');
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
