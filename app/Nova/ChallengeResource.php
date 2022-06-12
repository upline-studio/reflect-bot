<?php

namespace App\Nova;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class ChallengeResource extends Resource
{
    public static $model = Challenge::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name'
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable()->rules('required'),
            Textarea::make('Description')->rules('required'),
            Number::make('Sort order')->sortable()->default(0)
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
