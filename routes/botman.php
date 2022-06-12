<?php

use App\Commands\HelpCommand;
use App\Commands\StartCommand;
use App\Commands\StudyCommand;
use App\Conversations\ChallengeConversation;
use App\Conversations\NewSubjectConversation;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

$botman->hears('/start', new StartCommand());

$botman->hears('/help', new HelpCommand());

$botman->hears('/new_subject', function (BotMan $bot) {
    $bot->startConversation(new NewSubjectConversation());
});

$botman->hears('/reflection', new StudyCommand());

$botman->hears('/challenge', function (BotMan $bot) {
   $bot->startConversation(new ChallengeConversation());
});
