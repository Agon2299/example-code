<?php


namespace App\Entities\Promotion\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\Offer\NovaResources\OfferNovaResource;
use App\Entities\Promotion\FiltersNova\PromotionNameFilterNova;
use App\Entities\Promotion\FiltersNova\PromotionOfferNameFilterNova;
use App\Entities\Promotion\FiltersNova\PromotionOnHomeFilterNova;
use App\Entities\Promotion\FiltersNova\PromotionOnMainFilterNova;
use App\Entities\Promotion\FiltersNova\PromotionShopNameFilterNova;
use App\Entities\Promotion\Models\Promotion;
use App\Entities\Shop\NovaResources\ShopNovaResource;
use App\Nova\Actions\ExportFile;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use PosLifestyle\DateRangeFilter\DateRangeFilter;
use PosLifestyle\DateRangeFilter\Enums\Config;

class PromotionsNovaResource extends BaseNovaResource
{

    public static $model = Promotion::class;
    public static $category = 'Публикации';
    public static $defaultSort = 'created_at';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Акции';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Акция';
    }

    public function filters(Request $request)
    {
        return [
            new PromotionNameFilterNova(),
            new PromotionShopNameFilterNova(),
            new PromotionOfferNameFilterNova(),
            new DateRangeFilter('Фильтр по дате создания акции', 'created_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате публикации акции', 'publish_start_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате начала акции', 'start_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате окончания акции', 'end_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new PromotionOnMainFilterNova(),
            new PromotionOnHomeFilterNova()
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
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    protected function getFields(): array
    {
        return [
            ID::make('ID акции', 'id')->onlyOnDetail(),
            Images::make('Изображение', 'thumbnail')->rules('required'),

            Text::make('Название', 'title')->rules('required')->hideFromIndex()->sortable(),
            Text::make('Название', 'title', function () {
                return '<a href="/admin-panel/resources/promotions-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->title . '</a>';
            })->rules('required')->sortable()->onlyOnIndex()->asHtml(),

            Number::make('Порядок', 'priority')->rules('required')->sortable(),
            BelongsTo::make('Объект ТЦ', 'shop', ShopNovaResource::class)->nullable(),
            BelongsTo::make('Предложение (подарок)', 'offer', OfferNovaResource::class)->nullable(),

            DateTime::make('Дата создания', 'created_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->rules('required')
                ->sortable(),

            DateTime::make('Дата публикации', 'publish_start_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required')
                ->sortable(),

            DateTime::make('Дата и время начала акции', 'start_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required')
                ->sortable(),

            DateTime::make('Дата и время окончания акции', 'end_at')
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required')
                ->sortable(),

            Markdown::make('Описание', 'content')->hideFromIndex()->rules('required'),
            Text::make('Краткое описание', 'disclaimer')->hideFromIndex()->nullable(),
            Text::make('Сссылка на карту', 'sitemapLinkShop')->onlyOnDetail(),

            Boolean::make('В слайдере на главном экране', 'on_main')->sortable(),
            Boolean::make('На главном экране', 'on_home')->sortable(),
        ];
    }
}
