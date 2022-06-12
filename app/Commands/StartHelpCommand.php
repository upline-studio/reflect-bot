<?php

namespace App\Commands;

use BotMan\BotMan\BotMan;

class StartHelpCommand extends ChatCommand
{
    public function run(BotMan $botMan)
    {
        $botMan->reply('Теперь я готов рассказать что я умею.');
        HelpCommand::make()->run($botMan);
    }
}
