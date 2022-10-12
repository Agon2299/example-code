<?php


namespace App\Entities\Offer\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\ActivatedOffer\NovaResources\ActivatedOfferNovaResource;
use App\Entities\MobileUser\NovaResources\MobileUserNovaResource;
use App\Entities\Offer\ActionsNova\OfferDetachMobileUserAction;
use App\Entities\Offer\FiltersNova\OfferCoastFilterNova;
use App\Entities\Offer\FiltersNova\OfferNameFilterNova;
use App\Entities\Offer\FiltersNova\OfferOnHomeFilterNova;
use App\Entities\Offer\FiltersNova\OfferShopNameFilterNova;
use App\Entities\Offer\FiltersNova\OfferTypeFilterNova;
use App\Entities\Offer\Models\Offer;
use App\Entities\Promotion\NovaResources\PromotionsNovaResource;
use App\Entities\Shop\NovaResources\ShopNovaResource;
use App\Entities\Transaction\NovaResources\TransactionNovaResource;
use App\Nova\Actions\ExportFile;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use PosLifestyle\DateRangeFilter\Enums\Config;

class OfferMobileUserNovaResource extends BaseNovaResource
{

    public static $model = Offer::class;
    public static $category = 'Публикации';
    public static $defaultSort = 'created_at';
    public static $displayInNavigation = false;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Предложения';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Предложение';
    }

    public function filters(Request $request)
    {
        return [
            new OfferNameFilterNova(),
            new OfferShopNameFilterNova(),
            new OfferTypeFilterNova(),
            new DateRangeFilter('Фильтр по дате создания', 'created_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате начала', 'start_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате окончания', 'end_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new OfferCoastFilterNova(),
            new OfferOnHomeFilterNova(),
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->name;
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request): array
    {
        return [
            (new ExportFile)->withHeadings()->askForWriterType()->askForFilename(),
            (new OfferDetachMobileUserAction)->withoutActionEvents()->withMeta([
                'mobile_user_id' => $request->query('viaResourceId')
            ]),
        ];
    }

    protected function getFields(): array
    {
        return [
            ID::make('ID предложения', 'id')->onlyOnDetail(),
            Images::make('Изображение', 'thumbnail')->rules('required')->nullable(),

            Text::make('Название', 'name', function () {
                return '<a href="/admin-panel/resources/offer-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->name . '</a>';
            })->onlyOnIndex()->sortable()->asHtml(),
            Text::make('Название', 'name')->hideFromIndex()->rules('required'),

            Number::make('Порядок', 'priority')->rules('required')->sortable(),
            BelongsTo::make('Объект ТЦ', 'shop', ShopNovaResource::class)->nullable(),

            Text::make('Дисклеймер', 'disclaimer')
                ->sortable()
                ->nullable(),

            Textarea::make('Описание', 'description')
                ->rows(5)
                ->rules('required')
                ->hideFromIndex(),

            Textarea::make('Условия получения', 'conditions_receiving')
                ->rows(5)
                ->rules('required')
                ->hideFromIndex(),

            Textarea::make('Условия подтверждения', 'confirmation_conditions')
                ->rows(5)
                ->rules('required')
                ->hideFromIndex(),

            Select::make('Тип', 'type')->options([
                'single' => 'Single',
                'multiple' => 'Multiple',
                'welcome_offer' => 'Welcome Offer',
            ])->rules('required')->displayUsingLabels()->sortable(),
            Number::make('Количество подарков', 'count')
                ->hideFromIndex()
                ->rules('required'),

            DateTime::make('Дата создания', 'created_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            DateTime::make('Дата начала', 'start_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required')
                ->sortable(),
            DateTime::make('Дата окончания', 'end_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required')
                ->sortable(),

            Number::make('Цена', 'cost')->rules('required')->sortable(),
            Boolean::make('На главном экране', 'on_home')->sortable(),

            HasMany::make('Акция', 'promotion', PromotionsNovaResource::class),
            BelongsToMany::make('Пользователи', 'mobileUsers', MobileUserNovaResource::class),
            HasMany::make('Пользователи которые использовали предложение', 'activatedOffers', ActivatedOfferNovaResource::class),
            MorphMany::make('Транзакции покупки предложения', 'transactions', TransactionNovaResource::class),
        ];
    }
}
