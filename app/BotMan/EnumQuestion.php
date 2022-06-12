<?php

namespace App\BotMan;

use BenSampo\Enum\Enum;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class EnumQuestion extends Question
{
    public function addButtonsFromEnum($enum)
    {
        $instances = $enum::getInstances();
        $buttons = array_map(fn(Enum $instance) => Button::create($instance->description)->value($instance->value), $instances);
        return $this->addButtons($buttons);
    }
}
