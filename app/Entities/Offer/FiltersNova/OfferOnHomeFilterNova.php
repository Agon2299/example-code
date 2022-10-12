<?php

namespace App\Entities\Offer\FiltersNova;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class OfferOnHomeFilterNova extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр отображение в слайдере на главном экране';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('on_home', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Да' => true,
            'Нет' => false,
        ];
    }
}
