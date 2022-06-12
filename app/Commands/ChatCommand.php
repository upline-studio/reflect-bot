<?php

namespace App\Commands;

use BotMan\BotMan\BotMan;

abstract class ChatCommand
{
    abstract public function run(BotMan $botMan);

    public function __invoke(BotMan $botMan)
    {
        $this->run($botMan);
    }
    /**
     * Create a new element.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }
}
