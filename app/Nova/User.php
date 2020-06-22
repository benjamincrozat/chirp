<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \App\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(Request $request) : array
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->rules('required', 'max:255'),

            Text::make('Nickname')
                ->rules('required', 'max:255'),

            Text::make('Token')
                ->nullable()
                ->hideFromIndex(),

            Text::make('Token Secret')
                ->nullable()
                ->hideFromIndex(),

            Code::make('Data')
                ->json()
                ->nullable(),

            HasMany::make('Likes'),

            HasMany::make('Followers'),

            HasMany::make('Followings'),

            HasMany::make('Muted'),

            HasMany::make('Blocked'),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(Request $request) : array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(Request $request) : array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(Request $request) : array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(Request $request) : array
    {
        return [];
    }
}
