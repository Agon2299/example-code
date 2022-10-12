<?php

namespace App\Entities\Offer\FiltersNova;

use Illuminate\Http\Request;
use Reedware\NovaTextFilter\TextFilter;

class OfferShopNameFilterNova extends TextFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр по названию Объекта ТЦ';

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
        return $query->whereHas('shop', static function ($q) use ($value) {
            return $q->where('shops.name', 'ilike', '%' . $value . '%');
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }
}
