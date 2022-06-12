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

    public function getEmoji(): ?string
    {
        switch ($this->value) {
            case self::GOOD:
                return '😃';
            case self::OK:
                return '😐';
            case self::BAD:
                return '🙁';
        }

        return null;
    }
}
