<?php

namespace App\Service\Chat;

use App\Models\Chat;

class ChatService
{
    public function registerChat(ChatData $data): Chat
    {
        $chat = Chat::where('chat_id', $data->getChatId())->first();
        if ($chat) {
            return $chat;
        }

        $chat = new Chat();
        $chat->chat_id = $data->getChatId();
        $chat->username = $data->getUserName();
        $chat->first_name = $data->getFirstName();
        $chat->last_name = $data->getLastName();
        $chat->bio = $data->getBio();
        $chat->save();

        return $chat;
    }
}
