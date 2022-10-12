<?php

namespace App\Entities\SmsCode\Listeners;

use App\Entities\SmsCode\Models\SmsCode;
use App\Entities\SmsCode\Services\SmsCodeService;
use Nutnet\LaravelSms\SmsSender;

class SendSmsForChangePhoneListener
{
    public SmsCodeService $smsCodeService;
    public SmsSender $smsSender;

    /**
     * Create the event listener.
     *
     * @param SmsCodeService $smsCodeService
     */
    public function __construct(SmsCodeService $smsCodeService, SmsSender $smsSender)
    {
        $this->smsCodeService = $smsCodeService;
        $this->smsSender = $smsSender;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {
        $code = $this->generateCode();
        $this->smsCodeService->createOrUpdateSmsCode($event->mobileUser->id, $code);

        $this->smsSender->send($event->phone, 'Код: ' . $code);
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
