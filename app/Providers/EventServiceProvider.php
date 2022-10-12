<?php

namespace App\Providers;

use App\Entities\MobileUser\Events\MobileUserDetachOffer;
use App\Entities\MobileUser\Listeners\MobileUserDetachOfferListener;
use App\Entities\Offer\Events\DetachOffer;
use App\Entities\Offer\Events\OfferDetachMobileUser;
use App\Entities\Offer\Listeners\DetachOfferListener;
use App\Entities\Offer\Listeners\OfferDetachMobileUserListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Entities\MobileDevice\Listeners\SendCampaignMobileDevice;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NotificationSent::class => [
            SendCampaignMobileDevice::class,
        ],
        OfferDetachMobileUser::class => [
            OfferDetachMobileUserListener::class,
        ],
        MobileUserDetachOffer::class => [
            MobileUserDetachOfferListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
