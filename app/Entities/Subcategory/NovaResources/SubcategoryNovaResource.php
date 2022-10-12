<?php


namespace App\Entities\Subcategory\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\Category\NovaResources\CategoryNovaResource;
use App\Entities\Subcategory\Models\Subcategory;
use App\Nova\Actions\ExportFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;

class SubcategoryNovaResource extends BaseNovaResource
{

    public static $model = Subcategory::class;
    public static $category = 'Объекты ТРЦ';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Подкатегории';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Подкатегория';
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
            BelongsToMany::make('Категория', 'categories', CategoryNovaResource::class)->sortable(),
        ];
    }
}
