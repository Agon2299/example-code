<?php

namespace App\Entities\Offer\Listeners;

use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\SmsCode\Models\SmsCode;
use App\Entities\SmsCode\Notifications\SendSmsCode;
use App\Entities\SmsCode\Services\SmsCodeService;

class OfferDetachMobileUserListener
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
        dd($event->request->resourceId);
        $mobileUser = MobileUser::find($event->request->resourceId);
        dd($mobileUser);
        $transaction = $mobileUser->transactions()->where('transactionstable_id', $event->object->id)->latest()->first();
        dd($transaction);


        $event->object->mobileUsers->detach($event->request->resourceId);
    }
}
