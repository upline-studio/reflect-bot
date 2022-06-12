<?php

namespace App\Commands;

use App\Conversations\StudyConversation;
use BotMan\BotMan\BotMan;

class StudyCommand extends ChatCommand
{
    public function run(BotMan $botMan)
    {
        $botMan->startConversation(new StudyConversation());
    }
}
