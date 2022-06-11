<?php

namespace App\Conversations;

use App\Enums\QuestionType;
use App\Service\QuestionService;
use Illuminate\Foundation\Inspiring;
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
        $questionService = app(QuestionService::class);
        $question = Question::create(
            $questionService
                ->getRandomQuestion(QuestionType::HOW_YOUR_STUDY())
                ->getRandomVariant()
        )
            ->fallback('Unable to ask question')
            ->callbackId('ask_study_reflection')
            ->addButtons([
                Button::create('😒 что-то не очень')->value('bad'),
                Button::create('😕 пойдет')->value('ok'),
                Button::create('😃 супер')->value('good'),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $value = $answer->getValue();
                switch ($value) {
                    case 'bad':
                        $this->say('А шо такое?');
                        break;
                    case 'ok':
                        $this->say('Кул');
                        break;
                    case 'good':
                        $this->say('Молодец, как 🥒🧂');
                        break;
                }
            }
            // TODO ask theme questions
        });
    }
}
