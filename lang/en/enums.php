<?php

use App\Enums\WhatsGoingOnAnswers;
use App\Enums\YesNoEnum;

return [
    YesNoEnum::class => [
        YesNoEnum::YES => 'Да',
        YesNoEnum::NO => 'Нет'
    ],
    WhatsGoingOnAnswers::class => [
        WhatsGoingOnAnswers::I_AM_CONFUSED => 'Вы запутались и не можете разобраться?',
        WhatsGoingOnAnswers::I_AM_BORED => 'Вам наскучил изучаемый предмет?',
        WhatsGoingOnAnswers::I_KNOW_EVERYTHING => 'Вы и так все это знаете?',
        WhatsGoingOnAnswers::DONT_NOW_WHY_I_AM_STUDYING => 'Вы не понимаете зачем вам это нужно?',
        WhatsGoingOnAnswers::SOMETHING_ELSE => 'Другое',
    ]
];
