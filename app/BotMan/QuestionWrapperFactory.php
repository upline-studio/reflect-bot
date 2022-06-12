<?php

namespace App\BotMan;

use App\Enums\QuestionType;
use App\Service\QuestionService;

class QuestionWrapperFactory
{
    public function getQuestionWrapper(QuestionType $questionType): QuestionWrapper
    {
        $questionService = app(QuestionService::class);

        $questionModel = $questionService
            ->getRandomQuestion($questionType);

        return new QuestionWrapper(
            $questionModel,
            $questionModel->getRandomVariant()
        );
    }
}
