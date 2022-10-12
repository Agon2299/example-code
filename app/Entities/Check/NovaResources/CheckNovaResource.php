<?php


namespace App\Entities\Check\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\Cashbox\NovaResources\CashboxNovaResource;
use App\Entities\Check\FiltersNova\CheckAmountFilterNova;
use App\Entities\Check\FiltersNova\CheckChangeBalanceFilterNova;
use App\Entities\Check\FiltersNova\CheckFiscalsignFilterNova;
use App\Entities\Check\FiltersNova\CheckMobileUserPhoneFilterNova;
use App\Entities\Check\FiltersNova\CheckNumberCashboxFilterNova;
use App\Entities\Check\FiltersNova\CheckNumberFilterNova;
use App\Entities\Check\FiltersNova\CheckShopNameFilterNova;
use App\Entities\Check\Models\Check;
use App\Entities\MobileUser\NovaResources\MobileUserNovaResource;
use App\Entities\Shop\NovaResources\ShopNovaResource;
use App\Nova\Actions\ExportFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use PosLifestyle\DateRangeFilter\Enums\Config;

class CheckNovaResource extends BaseNovaResource
{

    public static $model = Check::class;
    public static $category = 'Клиенты';
    public static $defaultSort = 'created_at';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Чеки';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Чек';
    }

    public function filters(Request $request)
    {
        return [
            new DateRangeFilter('Фильтр по дате сканирования чека', 'created_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),

            new CheckMobileUserPhoneFilterNova(),
            new CheckChangeBalanceFilterNova(),
            new CheckAmountFilterNova(),
            new CheckNumberFilterNova(),

            new DateRangeFilter('Фильтр по дате пробития чека', 'purchase_date', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),

            new CheckFiscalsignFilterNova(),
            new CheckNumberCashboxFilterNova(),
            new CheckShopNameFilterNova(),
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
            (new ExportFile)->except('idTransaction')->withHeadings()->askForWriterType()->askForFilename(),
        ];
    }

    protected function getFields(): array
    {
        return [
            Text::make('ID чека', 'id', function () {
                return '<a href="/admin-panel/resources/check-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->id . '</a>';
            })->hideWhenUpdating()->hideWhenCreating()->sortable()->asHtml(),

            DateTime::make('Дата сканирования чека', 'created_at')
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->format('DD.MM.YYYY HH:mm:ss')
                ->sortable(),
            BelongsTo::make('Пользователь', 'mobileUser', MobileUserNovaResource::class)->searchable(),
            Number::make('Начислено баллов', 'amountTransaction')->hideWhenUpdating()->hideWhenCreating()->asHtml(),

            Text::make('Сумма чека', 'amount')->sortable(),
            Text::make('Номер чека (ФД или ФПД)', 'number')->sortable(),

            DateTime::make('Дата пробития чека', 'purchase_date')->format('DD.MM.YYYY HH:mm:ss')->sortable(),
            Text::make('Фискальный признак (ФП)', 'fiscalsign'),

            BelongsTo::make('Магазин', 'shop', ShopNovaResource::class)->searchable(),
            BelongsTo::make('Касса (ФН)', 'cashbox', CashboxNovaResource::class)->searchable(),

            Text::make('ID транзакции', 'idTransaction')->hideWhenUpdating()->hideWhenCreating()->asHtml()
        ];
    }
}
