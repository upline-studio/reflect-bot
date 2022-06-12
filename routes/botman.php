<?php

use App\Commands\HelpCommand;
use App\Commands\StartCommand;
use App\Conversations\ChallengeConversation;
use App\Conversations\NewSubjectConversation;
use App\Conversations\StudyConversation;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

$botman->hears('/start', new StartCommand());

$botman->hears('/help', new HelpCommand());

$botman->hears('/new-subject', function (BotMan $bot) {
    $bot->startConversation(new NewSubjectConversation());
});

$botman->hears('/reflection', function (BotMan $bot) {
    $bot->startConversation(new StudyConversation());
});

$botman->hears('/challenge', function (BotMan $bot) {
   $bot->startConversation(new ChallengeConversation());
});
