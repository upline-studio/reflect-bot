<?php

use App\Conversations\NewSubjectConversation;
use App\Conversations\StudyConversation;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

$botman->hears('/start', function(BotMan $bot)
{
    $bot->startConversation(new NewSubjectConversation());
});

$botman->hears('/new-subject', function(BotMan $bot)
{
    $bot->startConversation(new NewSubjectConversation());
});

$botman->hears('/learnt', function(BotMan $bot)
{
    $bot->startConversation(new StudyConversation());
});
