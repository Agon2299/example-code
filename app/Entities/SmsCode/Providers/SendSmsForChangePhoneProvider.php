<?php

namespace App\Entities\SmsCode\Providers;

use App\Entities\SmsCode\Events\SendSmsForChangePhoneEvent;
use App\Entities\SmsCode\Listeners\SendSmsForChangePhoneListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class SendSmsForChangePhoneProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SendSmsForChangePhoneEvent::class => [
            SendSmsForChangePhoneListener::class,
        ],
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
