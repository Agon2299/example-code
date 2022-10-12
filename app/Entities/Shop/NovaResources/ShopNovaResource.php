<?php


namespace App\Entities\Shop\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\Cashbox\NovaResources\CashboxNovaResource;
use App\Entities\Category\NovaResources\CategoryNovaResource;
use App\Entities\Event\NovaResources\EventsNovaResource;
use App\Entities\News\NovaResources\NewsNovaResource;
use App\Entities\Offer\NovaResources\OfferNovaResource;
use App\Entities\Promotion\NovaResources\PromotionsNovaResource;
use App\Entities\Shop\FiltersNova\ShopCategoryNameFilterNova;
use App\Entities\Shop\FiltersNova\ShopNameFilterNova;
use App\Entities\Shop\FiltersNova\ShopSubcategoryNameFilterNova;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\NovaResources\SubcategoryNovaResource;
use App\Entities\Tag\NovaResources\TagNovaResource;
use App\Nova\Actions\ExportFile;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class ShopNovaResource extends BaseNovaResource
{

    public static $model = Shop::class;
    public static $category = 'Объекты ТРЦ';
    public static $defaultSort = 'name';
    public static $search = ['name'];

    public static function authorizable()
    {
        return false;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Объекты ТЦ';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Объект ТЦ';
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param Request $request
     *
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
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
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->name;
    }

    public function filters(Request $request)
    {
        return [
            new ShopCategoryNameFilterNova(),
            new ShopSubcategoryNameFilterNova(),
            new ShopNameFilterNova(),
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

    protected function getFields(): array
    {
        return [
            ID::make('Id')->hideFromIndex(),
            ID::make('Crm Id', 'crm_id')->hideFromIndex(),
            Text::make('Название', 'name', function () {
                return '<a href="/admin-panel/resources/shop-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->name . '</a>';
            })->sortable()->onlyOnIndex()->asHtml(),
            Text::make('Название', 'name')->hideFromIndex(),
            Text::make('Название транслитом', 'name_translate')->hideFromIndex(),
            BelongsToMany::make('Категория', 'categories', CategoryNovaResource::class)->sortable(),
            Text::make('Подкатегории', 'customSubcategory')->onlyOnIndex(),
            Text::make('Категории', 'customCategory')->onlyOnIndex(),
            HasMany::make('Новости', 'news', NewsNovaResource::class)->sortable(),
            HasMany::make('Акции', 'promotions', PromotionsNovaResource::class)->sortable(),
            HasMany::make('Мероприятия', 'events', EventsNovaResource::class)->sortable(),
            Images::make('Лого', 'thumbnail')->rules('required'),
            Text::make('Ссылка на сайт', 'site_url')->hideFromIndex(),
            Text::make('Inn', 'inn'),
            Boolean::make('Показывать на главном экране', 'show_in_home_display')->sortable(),
            Number::make('Кэшбэк', 'cashback')->min(0),
            Number::make('Этаж', 'floor')->min(0)->max(4)->step(1)->sortable(),
            BelongsToMany::make('Подкатегории', 'subcategories', SubcategoryNovaResource::class),
            Text::make('Описание', 'descriptionCustom')->hideFromIndex(),
            Text::make('Ссылка на карту', 'map')->hideFromIndex(),
            Images::make('Изображения', 'images')->rules('required'),
            BelongsToMany::make('Теги', 'tags', TagNovaResource::class),
            HasMany::make('Кассы', 'cashbox', CashboxNovaResource::class),
            HasMany::make('Предложения', 'offers', OfferNovaResource::class)
        ];
    }
}
