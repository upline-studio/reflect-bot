<?php

namespace App\Commands;

use App\BotMan\QuestionWrapperFactory;
use App\Enums\ChallengeAttitude;
use App\Models\Challenge;
use App\Service\Chat\ChatService;
use App\Service\QuestionService;
use BotMan\BotMan\BotMan;

class AllChallengesCompletedCommand extends ChatCommand
{
    protected ChatService $chatService;

    public function __construct()
    {
        $this->chatService = app(ChatService::class);
    }

    public function run(BotMan $botMan)
    {
        $chat = $this->chatService->getChatFromBotMan($botMan);
        $challenges = $chat
            ->challenges()
            ->withPivot(['did_like', 'would_use'])
            ->orderBy('sort_order')
            ->get()
            ->map(function (Challenge $challenge) {
                $attitude = $challenge->pivot->did_like ? ChallengeAttitude::fromValue($challenge->pivot->did_like)->getEmoji() : '✖';
                return "$challenge->name $attitude";
            })
            ->join("\n");

        $botMan->reply($challenges);
    }
}
