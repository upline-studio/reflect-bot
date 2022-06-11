<?php

namespace App\Nova;

use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use SimpleSquid\Nova\Fields\Enum\Enum;
use NovaItemsField\Items;

class QuestionResource extends Resource
{
    public static $model = Question::class;

    public static $title = 'id';

    public static $search = [
        'id', 'question_type'
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Enum::make('Question Type')
                ->sortable()
                ->attach(QuestionType::class)
                ->rules('required'),

            Items::make('Variants')

        ];
    }

    public function cards(Request $request): array
    {
        return [];
    }

    public function filters(Request $request): array
    {
        return [];
    }

    public function lenses(Request $request): array
    {
        return [];
    }

    public function actions(Request $request): array
    {
        return [];
    }
}
