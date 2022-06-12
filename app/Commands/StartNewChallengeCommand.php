<?php

namespace App\Commands;

use App\Models\Challenge;
use App\Models\Chat;
use App\Service\Chat\ChatService;
use BotMan\BotMan\BotMan;

class StartNewChallengeCommand extends ChatCommand
{
    protected ChatService $chatService;

    public function __construct()
    {
        $this->chatService = new ChatService();
    }

    public function run(BotMan $botMan)
    {
        $chat = $this->chatService->getChatFromBotMan($botMan);
        $challenge = $this->getAvailableChallenge($chat);

        if (empty($challenge)) {
            $botMan->reply('Ты прошел все челленджи!');
            return;
        }

        $botMan->reply('Челлендж: ' . $challenge->name);
        $botMan->reply($challenge->description);

        $chat->challenge_id = $challenge->id;
        $chat->save();

        $botMan->reply('Пробуй учиться этим методом!');
    }

    private function getAvailableChallenge(Chat $chat): ?Challenge
    {
        $completedChallenges = $chat->challenges->pluck('id')->all();
        return Challenge::whereNotIn('id', $completedChallenges)->orderBy('sort_order')->first();
    }
}
