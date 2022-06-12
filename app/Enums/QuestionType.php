<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static HOW_YOUR_STUDY()
 * @method static static BAD_EXPERIENCE()
 * @method static static OK_EXPERIENCE()
 * @method static static GOOD_EXPERIENCE()
 * @method static static WHAT_IS_SUBJECT()
 * @method static static WHAT_IS_GOAL()
 * @method static static IS_PREVIOUS_DIFFICULTIES_SOLVED()
 * @method static static HOW_YOU_SOLVE_PROBLEM()
 * @method static static TRY_TO_FIGURE_OUT()
 * @method static static WHATS_GOING_ON()
 * @method static static WHAT_HAVE_YOU_LEARNT_AFTER_START()
 * @method static static WHY_DO_YOU_LEARN()
 * @method static static TELL_ABOUT_DIFFICULTIES()
 * @method static static SEND_TO_MENTOR()
 */
final class QuestionType extends Enum
{
    const HOW_YOUR_STUDY = 'how-your-study';
    const BAD_EXPERIENCE = 'bad-experience';
    const OK_EXPERIENCE = 'ok-experience';
    const GOOD_EXPERIENCE = 'good-experience';

    const WHAT_IS_SUBJECT = 'subject';
    const WHAT_IS_GOAL = 'goal';

    const CHALLENGE = 'challenge';
    const ACCEPT_CHALLENGE = 'accept-challenge';


    const IS_PREVIOUS_DIFFICULTIES_SOLVED = 'is-previous-difficulties';
    const HOW_YOU_SOLVE_PROBLEM = 'how-you-solve-problem';

    const TRY_TO_FIGURE_OUT = 'try-to-figure-out';
    const WHATS_GOING_ON = 'whats-going-on';

    const WHAT_HAVE_YOU_LEARNT_AFTER_START = 'what-have-you-learnt-after-start';
    const WHY_DO_YOU_LEARN = 'why-do-you-learn';
    const TELL_ABOUT_DIFFICULTIES = 'tell-about-difficulties';
    const SEND_TO_MENTOR = 'send-to-mentor';
}
