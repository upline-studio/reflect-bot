<?php

namespace App\Events;

use App\Enums\ChatEventType;
use App\Models\Chat;
use Illuminate\Foundation\Events\Dispatchable;

class ChatEvent
{
    use Dispatchable;

    private Chat $chat;
    private ChatEventType $type;

    public function __construct(Chat $chat, ChatEventType $type)
    {
        $this->chat = $chat;
        $this->type = $type;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @return ChatEventType
     */
    public function getType(): ChatEventType
    {
        return $this->type;
    }


}
