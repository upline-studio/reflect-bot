<?php

use App\Conversations\OnBoardConversation;
use App\Conversations\StudyConversation;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

$botman->hears('/start', function(BotMan $bot)
{

    $bot->startConversation(new OnBoardConversation());
});

$botman->hears('/learnt', function(BotMan $bot)
{
    $bot->startConversation(new StudyConversation());
});
