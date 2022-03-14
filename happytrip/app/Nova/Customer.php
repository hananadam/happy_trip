<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;

class Customer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Customer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
     public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        
        return [
            ID::make()->sortable(),

            Avatar::make('photo')->maxWidth(50),

            Text::make('Title')
                ->sortable()
                ->rules('max:25'),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),
            Text::make('Dailing Code')
                ->sortable()
                ->rules('required', 'max:10'),
            Text::make('Mobile')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Gender')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Address')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Country')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Nationality')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('id_number')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('id_expiry_date')
                ->sortable()
                ->rules('required', 'max:255'),
        ];
            

    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
