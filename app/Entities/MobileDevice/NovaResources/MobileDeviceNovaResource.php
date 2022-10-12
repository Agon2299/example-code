<?php


namespace App\Entities\MobileDevice\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\MobileDevice\FiltersNova\MobileDeviceModelFilterNova;
use App\Entities\MobileDevice\FiltersNova\MobileDevicePhoneMobileUserFilterNova;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\MobileUser\NovaResources\MobileUserNovaResource;
use App\Nova\Actions\ExportFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class MobileDeviceNovaResource extends BaseNovaResource
{

    public static $model = MobileDevice::class;
    public static $category = 'Клиенты';
    public static $defaultSort = 'created_at';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Устройства';
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->id;
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Устройство';
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

    public function filters(Request $request)
    {
        return [
            new MobileDeviceModelFilterNova,
            new MobileDevicePhoneMobileUserFilterNova,
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
            (new ExportFile)->withHeadings()->askForWriterType()->askForFilename(),
        ];
    }

    protected function getFields(): array
    {
        return [
            Text::make('ID устройства', 'id', function (){
                return '<a href="/admin-panel/resources/mobile-device-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->id . '</a>';
            })->asHtml(),
            BelongsTo::make('Телефон', 'mobileUser', MobileUserNovaResource::class),
            DateTime::make('Дата установки', 'install_app')->format('DD.MM.YYYY HH:mm:ss')->sortable(),
            Text::make('Операционная система', 'os')->sortable(),
            Text::make('Версия ОС', 'version_os')->sortable(),
            Text::make('Модель', 'model'),
            Text::make('Mac адресс', 'mac_address'),
            Text::make('Уникальный номер устройства', 'idfa'),
        ];
    }
}
