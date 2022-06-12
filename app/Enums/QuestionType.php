<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class QuestionType extends Enum
{
    const HOW_YOUR_STUDY = 'how-your-study';
    const BAD_EXPERIENCE = 'bad-experience';
    const OK_EXPERIENCE = 'ok-experience';
    const GOOD_EXPERIENCE = 'good-experience';

    const WHAT_IS_SUBJECT = 'subject';
    const WHAT_IS_GOAL = 'goal';
}
