<?php

namespace App\Nova;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChatResource extends Resource
{
    public static $model = Chat::class;

    public static $title = 'id';

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToRunAction(NovaRequest $request, Action $action)
    {
        return Auth::user()->is_admin;
    }

    public static $search = [
        'id', 'chat_id'
    ];

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Chat Id')
                ->sortable()
                ->rules('required')
                ->readonly(),

            Text::make('Username')
                ->sortable()
                ->rules('nullable')
                ->readonly(),

            Text::make('First Name')
                ->sortable()
                ->rules('nullable')
                ->readonly(),

            Text::make('Last Name')
                ->sortable()
                ->rules('nullable')
                ->readonly(),

            Text::make('Bio')
                ->sortable()
                ->rules('nullable')
                ->readonly(),
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
        return [
        ];
    }
}
