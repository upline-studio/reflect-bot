<?php

namespace App\Listeners;

use App\Conversations\StudyConversation;
use App\Enums\ChatEventType;
use App\Events\ChatEvent;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;

class ChatEventListener
{
    public function __construct()
    {
        //
    }

    public function handle(ChatEvent $event)
    {
        /** @var BotMan $botman */
        $botman = app('botman');
        switch ($event->getType()) {
            case ChatEventType::LEARNT:
                $botman->startConversation(
                    new StudyConversation(),
                    $event->getChat()->chat_id,
                    TelegramDriver::class
                );
                break;
        }
    }
}
