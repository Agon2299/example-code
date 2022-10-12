<?php

namespace App\Entities\SmsCode\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Entities\SmsCode\Events\SendSmsEvent;
use App\Entities\SmsCode\Listeners\SendSmsListener;

class SendSmsProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SendSmsEvent::class => [
            SendSmsListener::class,
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
