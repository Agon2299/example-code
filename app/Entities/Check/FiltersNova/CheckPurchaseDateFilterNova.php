<?php

namespace App\Entities\Check\FiltersNova;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class CheckPurchaseDateFilterNova extends DateFilter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр по дате пробития чека';

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
        return $query->where('purchase_date', '>=', Carbon::parse($value)->startOfDay())
            ->where('purchase_date', '<=', Carbon::parse($value)->endOfDay());
    }
}
