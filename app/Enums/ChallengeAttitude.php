<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;


final class ChallengeAttitude extends Enum implements LocalizedEnum
{
    const GOOD = 'good';
    const OK = 'ok';
    const BAD = 'bad';
    const DONT_DECIDE = 'dont-decide';
}
