<?php


namespace App\Entities\MobileUser\NovaResources;


use App\Base\BaseNovaResource;
use App\Entities\ActivatedOffer\NovaResources\ActivatedOfferNovaResource;
use App\Entities\Check\NovaResources\CheckNovaResource;
use App\Entities\Event\NovaResources\EventsNovaResource;
use App\Entities\MobileUser\FiltersNova\MobileUserEmailFilterNova;
use App\Entities\MobileUser\FiltersNova\MobileUserHasTransactionRegisterFilterNova;
use App\Entities\MobileUser\FiltersNova\MobileUserNameFilterNova;
use App\Entities\MobileUser\FiltersNova\MobileUserPhoneFilterNova;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\Offer\NovaResources\OfferMobileUserNovaResource;
use App\Entities\Offer\NovaResources\OfferNovaResource;
use App\Entities\Transaction\NovaResources\TransactionNovaResource;
use App\Nova\Actions\ExportFile;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class MobileUserNovaResource extends BaseNovaResource
{

    public static $model = MobileUser::class;
    public static $category = 'Клиенты';
    public static $defaultSort = 'phone';
    public static $hasTransaction = false;
    public static $search = ['phone'];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Пользователи';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Пользователь';
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
            new MobileUserNameFilterNova(),
            new MobileUserPhoneFilterNova(),
            new MobileUserEmailFilterNova(),
            new MobileUserHasTransactionRegisterFilterNova(),
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->phone;
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

    protected function getFields(): array
    {
        return [
            ID::make('ID', 'id')->onlyOnDetail(),
            Text::make('ID устройства', 'customMobileDeviceId')->hideWhenUpdating()->hideWhenCreating()->asHtml(),
            DateTime::make('Дата установки приложения', 'dateInstall')->format('DD.MM.YYYY HH:mm:ss')->hideWhenUpdating()->hideWhenCreating(),

            Text::make('Телефон', 'phone', function () {
                return '<a href="/admin-panel/resources/mobile-user-nova-resources/' . $this->id . '" class="no-underline dim text-primary font-bold">' . $this->phone . '</a>';
            })->onlyOnIndex()->sortable()->asHtml(),
            Text::make('Телефон', 'phone')->hideFromIndex(),

            Text::make('Имя', 'name')->sortable()->onlyOnIndex(),
            Text::make('Имя', 'name')->onlyOnDetail(),

            Text::make('Почта', 'email')->sortable()->onlyOnIndex(),
            Text::make('Почта', 'email')->onlyOnDetail(),

            DateTime::make('Дата рождения', 'date_birth')->format('DD.MM.YYYY')->sortable(),
            Text::make('Пол', 'gender')->onlyOnDetail(),
            Boolean::make('Наличие детей', 'have_kids')->sortable(),
            Boolean::make('Регистрация по промокоду', 'registration_by_promo_code'),
            Text::make('Реферальный код', 'referral_code'),
            BelongsToMany::make('Мероприятия', 'events', EventsNovaResource::class),
            BelongsToMany::make('Предложения', 'offers', OfferMobileUserNovaResource::class),
            HasMany::make('Транзакции', 'transactionsMobileUser', TransactionNovaResource::class),
            HasMany::make('Использованные предложения', 'activatedOffers', ActivatedOfferNovaResource::class),
            HasMany::make('Чеки', 'checks', CheckNovaResource::class),

            Number::make('Кол-во приглашённых пользователей', 'countReferralUser'),
            Number::make('Кол-во зарегистрированных чеков ', 'countCheck'),
            Number::make('Общая сумма всех чеков', 'total_amount_of_all_checks'),
            Number::make('Средний чек', 'averageCheck'),

            Number::make('Баланс баллов', 'points_balance'),
            Number::make('Всего накоплено баллов', 'total_points_accumulated'),
            Number::make('Всего потрачено баллов', 'total_points_spent'),

            Number::make('Кол-во приобретённых предложений', 'countOffer'),
            Number::make('Кол-во приобретённых мероприятий', 'countEvent'),
            Number::make('Оценка приложения', 'appEvaluation'),
            Number::make('Количество раз поделился', 'count_share'),

            DateTime::make('Дата регистрации', 'dateRegistration')->format('DD.MM.YYYY HH:mm:ss'),
            Boolean::make('Пройден опрос после регистрации', 'existsSurvey'),
            Boolean::make('Активность пушей', 'enablePushAll')->hideWhenUpdating()->hideWhenCreating(),
            Boolean::make('Уведомление об акциях', 'enablePushPromotion')->showOnDetail()->onlyOnDetail(),
            Boolean::make('Уведомление о мероприятиях', 'enablePushEvent')->showOnDetail()->onlyOnDetail(),
            Boolean::make('Уведомление о детских мероприятиях', 'enablePushChildren')->showOnDetail()->onlyOnDetail(),
            Text::make('Статус', 'customStatusMobileUser')->onlyOnDetail(),
        ];
    }
}
