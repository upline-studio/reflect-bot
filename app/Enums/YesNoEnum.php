<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static YES()
 * @method static static NO()
 */
final class YesNoEnum extends Enum implements LocalizedEnum
{
    const YES = 'yes';
    const NO = 'no';
}
