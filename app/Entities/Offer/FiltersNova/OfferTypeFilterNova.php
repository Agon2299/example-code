<?php

namespace App\Entities\Offer\FiltersNova;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class OfferTypeFilterNova extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр по типу транзакции';

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
        return $query->where('type', $value);
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
            'Single' => 'single',
            'Multiple' => 'multiple',
            'Welcome Offer' => 'welcome_offer',
        ];
    }
}
