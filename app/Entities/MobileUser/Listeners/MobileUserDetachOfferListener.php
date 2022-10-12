<?php

namespace App\Entities\MobileUser\Listeners;

use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\SmsCode\Models\SmsCode;
use App\Entities\SmsCode\Notifications\SendSmsCode;
use App\Entities\SmsCode\Services\SmsCodeService;

class MobileUserDetachOfferListener
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event): void
    {
        $id = $event->request->resourceId;
        var_dump(123);
        dd($event);
    }
}
