<?php

namespace App\Entities\Transaction\Providers;

use App\Entities\Transaction\Events\AddTransactionEvent;
use App\Entities\Transaction\Listeners\AddTransactionListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AddTransactionProvider extends ServiceProvider
{
    protected $listen = [
        AddTransactionEvent::class => [
            AddTransactionListener::class,
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
