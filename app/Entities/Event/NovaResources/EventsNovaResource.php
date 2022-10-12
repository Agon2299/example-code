<?php


namespace App\Entities\Event\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\Event\FiltersNova\EventCostAtFilterNova;
use App\Entities\Event\FiltersNova\EventCostEndFilterNova;
use App\Entities\Event\FiltersNova\EventCreateAtFilterNova;
use App\Entities\Event\FiltersNova\EventEndAtFilterNova;
use App\Entities\Event\FiltersNova\EventOnHomeFilterNova;
use App\Entities\Event\FiltersNova\EventOnMainFilterNova;
use App\Entities\Event\FiltersNova\EventPublishAtFilterNova;
use App\Entities\Event\FiltersNova\EventShopNameFilterNova;
use App\Entities\Event\FiltersNova\EventStartAtFilterNova;
use App\Entities\Event\FiltersNova\EventTitleFilterNova;
use App\Entities\Event\Models\Event;
use App\Entities\MobileUser\NovaResources\MobileUserNovaResource;
use App\Entities\Shop\NovaResources\ShopNovaResource;
use App\Entities\Transaction\NovaResources\TransactionNovaResource;
use App\Nova\Actions\ExportFile;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use PosLifestyle\DateRangeFilter\Enums\Config;

class EventsNovaResource extends BaseNovaResource
{

    public static $model = Event::class;
    public static $category = 'Публикации';
    public static $defaultSort = 'created_at';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Мероприятия';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Мероприятие';
    }

    public function filters(Request $request)
    {
        return [
            new DateRangeFilter('Фильтр по дате создания', 'created_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате окончания', 'end_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new EventOnHomeFilterNova(),
            new EventOnMainFilterNova(),
            new DateRangeFilter('Фильтр по дате публикации', 'publish_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new EventShopNameFilterNova(),

            new DateRangeFilter('Фильтр по дате начала', 'start_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new EventTitleFilterNova(),
            new EventCostAtFilterNova(),
            new EventCostEndFilterNova(),
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

    public function title()
    {
        return $this->title;
    }

    protected function getFields(): array
    {
        return [
            ID::make('ID мероприятия', 'id')->onlyOnDetail(),
            Images::make('Изображение', 'thumbnail')->rules('required'),

            Text::make('Название', 'title', function () {
                return '<a href="/admin-panel/resources/events-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->title . '</a>';
            })->onlyOnIndex()->asHtml()->sortable(),
            Text::make('Название', 'title')->hideFromIndex()->rules('required')->sortable(),

            Number::make('Порядок', 'priority')->rules('required')->sortable(),
            BelongsTo::make('Объект ТЦ', 'shop', ShopNovaResource::class)->nullable(),
            Markdown::make('Описание', 'content')->rules('required')->hideFromIndex(),

            DateTime::make('Дата создания', 'created_at')->format('DD.MM.YYYY HH:mm:ss')->hideWhenCreating()->hideWhenUpdating()->sortable(),
            DateTime::make('Дата публикации', 'publish_at')->format('DD.MM.YYYY HH:mm:ss')->rules('required')->sortable(),
            DateTime::make('Дата начала', 'start_at')->format('DD.MM.YYYY HH:mm:ss')->rules('required')->sortable(),
            DateTime::make('Дата окончания', 'end_at')->format('DD.MM.YYYY HH:mm:ss')->rules('required')->sortable(),

            Text::make('Ссылку на карту', 'sitemapLinkCustom')->onlyOnDetail()->hideWhenUpdating()->hideWhenCreating(),
            Number::make('Цена', 'cost')->rules('required')->sortable(),

            Boolean::make('В слайдере на главном экране', 'on_main')->sortable(),
            Boolean::make('На главном экране', 'on_home')->sortable(),

            BelongsToMany::make('Мобильные пользователи', 'mobileUsers', MobileUserNovaResource::class),
            MorphMany::make('Транзакции', 'transactions', TransactionNovaResource::class),
        ];
    }
}
