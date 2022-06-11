<?php

namespace App\Conversations;

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
        $question = Question::create("Как сегодня поучился?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_material');
//            ->addButtons([
//                Button::create('Tell a joke')->value('joke'),
//                Button::create('Give me a fancy quote')->value('quote'),
//            ]);

        return $this->ask($question, function (Answer $answer) {
            $text = $answer->getText();
            // TODO onboarding question
        });
    }
}
