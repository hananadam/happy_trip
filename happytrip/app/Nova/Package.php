<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Code;
use App\Nova\Image;
use App\Nova\PackageDetail;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\HasMany;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

//use Naoray\NovaJson\JSON;
use R64\NovaFields\JSON;
class Package extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Package::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','title'
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
            ID::make(__('ID'), 'id')->sortable(),
            //MorphOne::make('Image'),

            Avatar::make('Main Image')->maxWidth(50),
            Text::make('Title')
                ->sortable()
                ->rules('max:200','required'),
            Text::make('Package Offer')
                ->sortable()
                ->rules('max:200','required'),
            Textarea::make('Description')
                ->sortable()
                ->rules('required'),
            Number::make('Old Price')
                ->sortable(),
            Number::make('Price')
                ->sortable(),
            //Number::make('Satus')
             //   ->sortable(),

            HasMany::make('PackageDetail'),

            JSON::make('Options', [
                Text::make('title')
            ],'options'),
            
            JSON::make('Policy', [
                Text::make('title'),
                Text::make('description')
            ],'policy'),

            Images::make('Images', 'packages') // second parameter is the media collection name
                ->conversionOnPreview('medium-size') // conversion used to display the "original" image
                ->conversionOnDetailView('thumb') // conversion used on the model's view
                ->conversionOnIndexView('thumb') // conversion used to display the image on the model's index page
                ->conversionOnForm('thumb') // conversion used to display the image on the model's form
                ->fullSize() // full size column
                // ->rules('size:3') // validation rules for the collection of images
                // validation rules for the collection of images
                ->singleImageRules('dimensions:min_width=100'),

            //Files::make('Multiple files', 'multiple_files'),



           
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
