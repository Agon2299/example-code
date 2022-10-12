<?php

namespace App\Entities\MobileUser\FiltersNova;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Reedware\NovaTextFilter\TextFilter;

class MobileUserHasTransactionRegisterFilterNova extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */

    public $name = 'Фильтр есть ли транзакция регистрации';

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
        return $query->when($value, function ($q) {
            return $q->whereHas('transactionsMobileUser', function ($query) {
                return $query->where('transactions.type', 'registration');
            });
        })->when(!$value, function ($q) {
            return $q->whereDoesntHave('transactionsMobileUser', function ($query) {
                return $query->where('transactions.type', 'registration');
            });
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
        return [
            'Да' => true,
            'Нет' => false
        ];
    }
}
