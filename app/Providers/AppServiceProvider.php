<?php

namespace App\Providers;

use App\Entities\BanMobileUser\Models\BanMobileUser;
use App\Entities\BanMobileUser\Observers\BanMobileUserObserver;
use App\Entities\Check\Models\Check;
use App\Entities\Check\Observers\CheckObserver;
use App\Entities\Transaction\Models\Transaction;
use App\Entities\Transaction\Observers\TransactionObserver;
use Illuminate\Http\Resources\Json\JsonResource as Resource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\URL::forceScheme('https');
        Resource::withoutWrapping();

        Check::observe(CheckObserver::class);
        Transaction::observe(TransactionObserver::class);
        BanMobileUser::observe(BanMobileUserObserver::class);
    }
}
