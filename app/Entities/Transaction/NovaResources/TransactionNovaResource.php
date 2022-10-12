<?php


namespace App\Entities\Transaction\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\MobileUser\NovaResources\MobileUserNovaResource;
use App\Entities\Reason\Models\Reason;
use App\Entities\Reason\NovaResources\ReasonNovaResource;
use App\Entities\Transaction\FiltersNova\TransactionTypeFilterNova;
use App\Entities\Transaction\Models\Transaction;
use App\Nova\Actions\ExportFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use PosLifestyle\DateRangeFilter\Enums\Config;

class TransactionNovaResource extends BaseNovaResource
{

    public static $model = Transaction::class;
    public static $category = 'Клиенты';
    public static $defaultSort = 'created_at';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Транзакции';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Транзакция';
    }

    public function filters(Request $request)
    {
        return [
            new TransactionTypeFilterNova(),
            new DateRangeFilter('Фильтр по дате транзакции', 'created_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new ExportFile)->withHeadings()->askForWriterType()->askForFilename(),
        ];
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource or throw an exception.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    protected function getFields(): array
    {
        return [
            Text::make('ID транзакции', 'id', function () {
                return '<a href="/admin-panel/resources/transaction-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->id . '</a>';
            })->asHtml()->hideWhenCreating()->hideWhenUpdating(),
            BelongsTo::make('Пользователь', 'mobileUser', MobileUserNovaResource::class)->searchable(),
            DateTime::make('Дата транзакции', 'created_at')->format('DD.MM.YYYY HH:mm:ss')->sortable()->hideWhenCreating()->hideWhenUpdating(),
            Text::make('Тип', 'typeTransaction')->hideWhenCreating()->hideWhenUpdating(),

            Select::make('Тип', 'type')->options([
                'accrual_admin' => 'Админ начисление',
                'deductions_admin' => 'Админ списание',
            ])->displayUsingLabels()->hideFromIndex()->hideFromDetail(),

            Text::make('Сумма чека', 'sumCheck')->hideWhenCreating()->hideWhenUpdating(),
            Number::make('Транзакция', 'change_balance')
                ->sortable()
                ->rules('required', function($attribute, $value, $fail) {
                    if ($value < 0) {
                        return $fail('Транзакция должна быть положительной');
                    }
                }),


            MorphTo::make('На что', 'transactionstable')->hideWhenCreating()->hideWhenUpdating(),
            MorphTo::make('На что', 'transactionstable')->types([
                ReasonNovaResource::class,
            ])->hideFromIndex()->hideFromDetail(),
        ];
    }
}
