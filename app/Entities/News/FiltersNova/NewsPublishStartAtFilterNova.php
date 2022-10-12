<?php

namespace App\Entities\News\FiltersNova;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class NewsPublishStartAtFilterNova extends DateFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр по дате начала публикации';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('publish_start_at', '>=', Carbon::parse($value)->startOfDay())
            ->where('publish_start_at', '<=', Carbon::parse($value)->endOfDay());
    }
}
