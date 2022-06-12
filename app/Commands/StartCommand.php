<?php

namespace App\Commands;

use App\Conversations\NewSubjectConversation;
use BotMan\BotMan\BotMan;

class StartCommand extends ChatCommand
{
    public function run(BotMan $botMan)
    {
        $botMan->reply('Привет. Я Бот помогающий проанализировать процесс и ход обучения. Я помогаю бороться с забыванием материала, помогаю оценить пройденный раздел. А так же помогаю опробовать новые подходы к обучению и посмотреть какие из них работают для вас, а какие нет.');
        $botMan->startConversation(new NewSubjectConversation(
            new StartHelpCommand()
        ));
    }
}
