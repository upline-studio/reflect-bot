<?php

namespace App\Conversations;

use App\Enums\QuestionType;
use App\Enums\StudyConversationActions;
use App\Enums\StudyExperienceType;
use App\Service\QuestionService;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class StudyConversation extends Conversation
{
    private QuestionService $questionService;

    private array $appraisalAnswers = [
        [
            StudyExperienceType::GOOD => [
                [
                    'text' => 'Напишите, что нового вы сегодня узнали?',
                    'action' => StudyConversationActions::TEXT
                ],
                [
                    'text' => 'Напишите, как вы можете применить изученные данные?',
                    'action' => StudyConversationActions::TEXT
                ],
                [
                    'text' => 'Столкнулись ли с чем-то, что стоит копнуть поглубже?',
                    'action' => StudyConversationActions::BOOLEAN
                ],
                [
                    'text' => 'Есть ли какие-то идеи, новые понимания?',
                    'action' => StudyConversationActions::BOOLEAN
                ],
            ],
            StudyExperienceType::OK => [
                [
                    'text' => 'Напишите, что нового вы сегодня узнали?',
                    'action' => StudyConversationActions::TEXT
                ],
                [
                    'text' => 'Напишите, как вы можете применить изученные данные?',
                    'action' => StudyConversationActions::TEXT
                ],
                [
                    'text' => 'Столкнулись ли с чем-то, что стоит копнуть поглубже?',
                    'action' => StudyConversationActions::BOOLEAN
                ],
                [
                    'text' => 'Есть ли какие-то идеи, новые понимания?',
                    'action' => StudyConversationActions::BOOLEAN
                ],
            ],
            StudyExperienceType::BAD => [
                [
                    'text' => 'Напишите, что нового вы сегодня узнали?',
                    'action' => StudyConversationActions::TEXT
                ],
                [
                    'text' => 'Напишите, как вы можете применить изученные данные?',
                    'action' => StudyConversationActions::TEXT
                ],
                [
                    'text' => 'Столкнулись ли с чем-то, что стоит копнуть поглубже?',
                    'action' => StudyConversationActions::BOOLEAN
                ],
                [
                    'text' => 'Есть ли какие-то идеи, новые понимания?',
                    'action' => StudyConversationActions::BOOLEAN
                ],
            ],
        ]

    ];

    /**
     * First question
     */
    public function run()
    {
        $this->questionService = app(QuestionService::class);
        $question = $this->getAppraisalQuestion();

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $value = StudyExperienceType::fromValue($answer->getValue());
                $this->getAppraisalAnswer($value);
//                switch ($value) {
//                    case 'bad':
//                        $this->
//                        $this->say('А шо такое?');
//                        break;
//                    case 'ok':
//                        $this->say('Кул');
//                        break;
//                    case 'good':
//                        $this->say('Молодец, как 🥒🧂');
//                        break;
//                }
            }
            // TODO ask theme questions
        });
    }

    private function getAppraisalQuestion(): Question
    {
        return Question::create(
            $this->questionService
                ->getRandomQuestion(QuestionType::HOW_YOUR_STUDY())
                ->getRandomVariant()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_study_reflection')
            ->addButtons([
                Button::create('😒 что-то не очень')->value(StudyExperienceType::BAD),
                Button::create('😕 пойдет')->value(StudyExperienceType::OK),
                Button::create('😃 супер')->value(StudyExperienceType::GOOD),
            ]);
    }

    private function getAppraisalAnswer(StudyExperienceType $experienceType)
    {
        switch ($experienceType) {
            case StudyExperienceType::BAD():
                $this->say('А шо такое?');
                break;
            case StudyExperienceType::OK():
                $this->say('Кул');
                break;
            case StudyExperienceType::GOOD():
                $this->say('Молодец, как 🥒🧂');
                break;
        }
    }
}
