<?php

namespace App\Providers;

use App\Entities\ActivatedOffer\NovaResources\ActivatedOfferNovaResource;
use App\Entities\AppFeedback\NovaResources\AppFeedbackNovaResource;
use App\Entities\BanMobileUser\NovaResources\BanMobileUserNovaResource;
use App\Entities\Campaign\NovaResources\CampaignNovaResource;
use App\Entities\CampaignStatus\NovaResources\CampaignStatusNovaResource;
use App\Entities\Cashbox\NovaResources\CashboxNovaResource;
use App\Entities\Category\NovaResources\CategoryNovaResource;
use App\Entities\Check\NovaResources\CheckNovaResource;
use App\Entities\Event\NovaResources\EventsNovaResource;
use App\Entities\MobileDevice\NovaResources\MobileDeviceNovaResource;
use App\Entities\MobileUser\NovaResources\MobileUserAuthNovaResource;
use App\Entities\MobileUser\NovaResources\MobileUserNovaResource;
use App\Entities\MobileUser\NovaResources\MobileUserOfferNovaResource;
use App\Entities\News\NovaResources\NewsNovaResource;
use App\Entities\Offer\NovaResources\OfferMobileUserNovaResource;
use App\Entities\Offer\NovaResources\OfferNovaResource;
use App\Entities\Promotion\NovaResources\PromotionsNovaResource;
use App\Entities\Reason\NovaResources\ReasonNovaResource;
use App\Entities\SearchQuery\NovaResources\SearchQueryNovaResource;
use App\Entities\Shop\NovaResources\ShopNovaResource;
use App\Entities\Subcategory\NovaResources\SubcategoryNovaResource;
use App\Entities\Tag\NovaResources\TagNovaResource;
use App\Entities\Transaction\NovaResources\TransactionNovaResource;
use App\Entities\User\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::serving(function () {
            App::setLocale('ru');
        });
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools(): array
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate(): void
    {
        Gate::define('viewNova', static function (User $user) {
            return $user->is_admin;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards(): array
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards(): array
    {
        return [];
    }

    /**
     *
     */
    protected function resources(): void
    {

        Nova::resources([
            NewsNovaResource::class,
            PromotionsNovaResource::class,
            EventsNovaResource::class,
            ShopNovaResource::class,
            CategoryNovaResource::class,
            SubcategoryNovaResource::class,
            MobileDeviceNovaResource::class,
            MobileUserNovaResource::class,
            AppFeedbackNovaResource::class,
            TagNovaResource::class,
            OfferNovaResource::class,
            SearchQueryNovaResource::class,
            TransactionNovaResource::class,
            CashboxNovaResource::class,
            CheckNovaResource::class,
            ActivatedOfferNovaResource::class,
            CampaignNovaResource::class,
            CampaignStatusNovaResource::class,
            BanMobileUserNovaResource::class,
            MobileUserAuthNovaResource::class,
            ReasonNovaResource::class,
            OfferMobileUserNovaResource::class,
            MobileUserOfferNovaResource::class,
        ]);
    }
}
