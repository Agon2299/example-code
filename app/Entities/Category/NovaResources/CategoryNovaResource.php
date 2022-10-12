<?php


namespace App\Entities\Category\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\Category\Models\Category;
use App\Nova\Actions\ExportFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;

class CategoryNovaResource extends BaseNovaResource
{

    public static $model = Category::class;
    public static $category = 'Объекты ТРЦ';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Категории';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Категория';
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
    public function actions(Request $request)
    {
        return [
            (new ExportFile)->withHeadings()->askForWriterType()->askForFilename(),
        ];
    }

    protected function getFields(): array
    {
        return [
            Text::make('Название', 'name'),
            Text::make('Слаг', 'slug'),
            Boolean::make('Есть поиск', 'is_search'),
            Boolean::make('Есть фильтр', 'has_filter'),
        ];
    }
}
