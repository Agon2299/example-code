<?php

namespace App\Entities\News\FiltersNova;

use Illuminate\Http\Request;
use Reedware\NovaTextFilter\TextFilter;

class NewsTitleFilterNova extends TextFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр по названию';

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
        return $query->where('title', 'ilike', '%' . $value . '%');
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
