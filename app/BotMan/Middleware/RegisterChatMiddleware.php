<?php

namespace App\BotMan\Middleware;

use App\Service\Chat\ChatData;
use App\Service\Chat\ChatService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Log;

class RegisterChatMiddleware implements Received
{
    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $chatService = app(ChatService::class);
        $user = $bot->getDriver()->getUser($message);
        $chat = $chatService->registerChat(
            new ChatData(
                $user->getId(),
                $user->getUsername(),
                $user->getFirstName(),
                $user->getLastName(),
                null,
            )
        );

        $message->addExtras('chat', $chat);
        return $next($message);
    }
}
