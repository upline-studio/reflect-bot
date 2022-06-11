<?php

use App\Conversations\OnBoardConversation;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

$botman->hears('/start', function(BotMan $bot)
{
    $bot->startConversation(new OnBoardConversation());
});
