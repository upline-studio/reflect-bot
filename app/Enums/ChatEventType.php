<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ChatEventType extends Enum
{
    const CHAPTER_FINISHED = 'chapter-finished';
    const LEARNT = 'learnt';
    const LOST = 'lost';
}
