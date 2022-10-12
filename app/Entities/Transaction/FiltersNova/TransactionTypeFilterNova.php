<?php

namespace App\Entities\Transaction\FiltersNova;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TransactionTypeFilterNova extends Filter
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
            'Реферальная программа (зашли по коду)' => 'referral_activated',
            'Реферальная программа (зашел по коду)' => 'referral_activation',
            'Покупка мероприятия' => 'buy_event',
            'Покупка предложения' => 'buy_offer',
            'Скан чека' => 'check',
            'Опрос' => 'survey',
            'Регистрация' => 'registration',
        ];
    }
}
