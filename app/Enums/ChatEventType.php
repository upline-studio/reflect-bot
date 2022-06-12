<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CHAPTER_FINISHED()
 * @method static static LEARNT()
 * @method static static LOST()
 */
final class ChatEventType extends Enum
{
    const CHAPTER_FINISHED = 'chapter-finished';
    const LEARNT = 'learnt';
    const LOST = 'lost';
}
