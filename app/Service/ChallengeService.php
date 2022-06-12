<?php

namespace App\Service;

use App\Models\Challenge;
use App\Models\Chat;
use App\Models\ChatChallenge;

class ChallengeService
{
    public function skipChallenge(Chat $chat, Challenge $challenge)
    {
        $chatChallenge = new ChatChallenge();
        $chatChallenge->chat_id = $chat->id;
        $chatChallenge->challenge_id = $challenge->id;
        $chatChallenge->is_skipped = true;
        $chatChallenge->save();
        $chat->challenge_id = null;
        $chat->save();
    }

    public function fillChallengeResult(ChallengeData $challengeData)
    {
        $chat = $challengeData->getChat();
        $chatChallenge = new ChatChallenge();
        $chatChallenge->chat_id = $chat->id;
        $chatChallenge->challenge_id = $challengeData->getChallenge()->id;
        $chatChallenge->is_skipped = false;
        $chatChallenge->would_use = $challengeData->isWouldUse();
        $chatChallenge->did_like = $challengeData->isDidLike();
        $chatChallenge->commentary = $challengeData->getComment();
        $chatChallenge->save();

        $chat->challenge_id = null;
        $chat->save();
    }

}
