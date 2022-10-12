<?php

namespace App\Entities\SmsCode\Notifications;

use App\Entities\SmsCode\Models\SmsCode;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsRu\SmsRuMessage;
use NotificationChannels\SmsRu\SmsRuChannel;

class SendSmsCode extends Notification
{
    private SmsCode $smsCode;

    /**
     * Create a new notification instance.
     *
     * @param SmsCode $smsCode
     */
    public function __construct(SmsCode $smsCode)
    {
        $this->smsCode = $smsCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsRuChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return SmsRuMessage
     */
    public function toSmsru($notifiable): SmsRuMessage
    {
        return (new SmsRuMessage())->content('Код: ' . $this->smsCode->code);
    }
}
