<?php

namespace App\Service\Chat;

use App\Models\Chat;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;

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

    public function getChat(int $chatId): Chat
    {
        return Chat::where('chat_id', $chatId)->first();
    }

    public function getChatFromAnswer(Answer $answer): Chat
    {
        return $answer->getMessage()->getExtras('chat')->first();
    }

    public function getChatFromBotMan(BotMan $botMan): Chat
    {
        return $this->getChat($botMan->getUser()->getId());
    }
}
