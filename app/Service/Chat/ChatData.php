<?php

namespace App\Service\Chat;

class ChatData
{
    private string $chatId;
    private ?string $userName;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $bio;

    public function __construct(string $chatId, ?string $userName, ?string $firstName, ?string $lastName, ?string $bio)
    {
        $this->chatId = $chatId;
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }
}
