<?php


namespace App\Entities\News\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\News\FiltersNova\NewsCreateAtFilterNova;
use App\Entities\News\FiltersNova\NewsOnMainFilterNova;
use App\Entities\News\FiltersNova\NewsPublishEndAtFilterNova;
use App\Entities\News\FiltersNova\NewsPublishStartAtFilterNova;
use App\Entities\News\FiltersNova\NewsShopNameFilterNova;
use App\Entities\News\FiltersNova\NewsTitleFilterNova;
use App\Entities\News\Models\News;
use App\Entities\Shop\NovaResources\ShopNovaResource;
use App\Nova\Actions\ExportFile;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
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

class NewsNovaResource extends BaseNovaResource
{

    public static $model = News::class;
    public static $category = 'Публикации';
    public static $defaultSort = 'created_at';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Новости';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Новость';
    }

    public function filters(Request $request)
    {
        return [
            new NewsTitleFilterNova(),
            new NewsShopNameFilterNova(),
            new NewsOnMainFilterNova(),

            new DateRangeFilter('Фильтр по дате создания новости', 'created_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате начала публикации', 'publish_start_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
            new DateRangeFilter('Фильтр по дате окончания публикации', 'publish_end_at', [
                Config::PLACEHOLDER => __('Укажите период'),
                Config::SHOW_MONTHS => 2,
            ]),
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

    protected function getFields(): array
    {
        return [
            ID::make('ID новости', 'id')->onlyOnDetail(),
            Images::make('Изображение', 'thumbnail')->rules('required'),
            Text::make('Название', 'title', function () {
                return '<a href="/admin-panel/resources/news-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->title . '</a>';
            })->sortable()->onlyOnIndex()->asHtml(),

            Text::make('Название', 'title')->rules('required')->hideFromIndex(),
            Number::make('Порядок', 'priority')->rules('required')->sortable(),
            BelongsTo::make('Объект ТЦ', 'shop', ShopNovaResource::class)->nullable(),

            DateTime::make('Дата создания', 'created_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable()
                ->format('DD.MM.YYYY HH:mm:ss'),

            DateTime::make('Дата начала публикации', 'publish_start_at')
                ->sortable()
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required'),

            DateTime::make('Дата окончания публикации', 'publish_end_at')
                ->sortable()
                ->format('DD.MM.YYYY HH:mm:ss')
                ->rules('required'),

            Markdown::make('Описание', 'content')->rules('required')->sortable(),

            Text::make('Ссылка на карту', 'sitemapLinkCustom')->onlyOnDetail(),
            Boolean::make('В слайдере на главном экране', 'on_main')->sortable(),
        ];
    }
}
