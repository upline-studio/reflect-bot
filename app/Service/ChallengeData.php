<?php

namespace App\Service;

use App\Enums\ChallengeAttitude;
use App\Models\Challenge;
use App\Models\Chat;

class ChallengeData
{
    private Chat $chat;
    private Challenge $challenge;
    private string $comment;
    private ChallengeAttitude $did_like;
    private bool $would_use;

    public function __construct(
        Chat $chat,
        Challenge $challenge,
        string $comment,
        ChallengeAttitude $did_like,
        bool $would_use
    )
    {
        $this->chat = $chat;
        $this->challenge = $challenge;
        $this->comment = $comment;
        $this->did_like = $did_like;
        $this->would_use = $would_use;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @return Challenge
     */
    public function getChallenge(): Challenge
    {
        return $this->challenge;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return ChallengeAttitude
     */
    public function isDidLike(): ChallengeAttitude
    {
        return $this->did_like;
    }

    /**
     * @return bool
     */
    public function isWouldUse(): bool
    {
        return $this->would_use;
    }
}
