<?php

namespace App\Entities\SmsCode\Listeners;

use App\Entities\SmsCode\Models\SmsCode;
use App\Entities\SmsCode\Notifications\SendSmsCode;
use App\Entities\SmsCode\Services\SmsCodeService;

class SendSmsListener
{
    public SmsCodeService $smsCodeService;

    /**
     * Create the event listener.
     *
     * @param SmsCodeService $smsCodeService
     */
    public function __construct(SmsCodeService $smsCodeService)
    {
        $this->smsCodeService = $smsCodeService;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event): void
    {
        $code = $this->generateCode();
        $smsCode = $this->smsCodeService->createOrUpdateSmsCode($event->mobileUser->id, $code);
        $event->mobileUser->notify(new SendSmsCode($smsCode));
    }

    public function generateCode(): string
    {
        $smsCode = '';
        do {
            for ($i = 0; $i < 6; $i++) {
                $smsCode .= random_int(0, 9);
            }
        } while (SmsCode::where('code', $smsCode)->first());

        return $smsCode;
    }
}
