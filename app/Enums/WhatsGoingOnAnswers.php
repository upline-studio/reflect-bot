<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;


final class WhatsGoingOnAnswers extends Enum implements LocalizedEnum
{
    const I_AM_CONFUSED = 'i-am-confused';
    const I_AM_BORED = 'i-am-bored';
    const I_KNOW_EVERYTHING = 'i-know-everything';
    const DONT_NOW_WHY_I_AM_STUDYING = 'dont-now-why-i-am-studying';
    const SOMETHING_ELSE = 'something-else';
}
