<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static BAD()
 * @method static static OK()
 * @method static static GOOD()
 */
final class StudyExperienceType extends Enum
{
    const BAD =   'bad';
    const OK =   'ok';
    const GOOD = 'good';
}
