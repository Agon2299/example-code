<?php

namespace App\Entities\MobileDevice\Notifications;

use App\Entities\Campaign\Models\Campaign;
use App\Entities\SmsCode\Models\SmsCode;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsRu\SmsRuMessage;
use NotificationChannels\SmsRu\SmsRuChannel;

class Ios extends Notification
{
    public $campaign;
    public $data;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return FcmMessage
     */
    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $object = $this->campaign->object;
        $link = $object ? 'riviera://' . $object->typeModelCampaign . '/' . $object->id : '';
        $data = [
            'title' => $this->campaign->title,
            'body' => $this->campaign->text,
            'sound' => 'default',
            'mutable_content' => true,
            'image' => $this->campaign->getThumbnailUrl() ?? null,
            'campaignId' => $this->campaign->id,
            'deeplink' => $link,
        ];
        $this->data = $data;


        $message->content(
            [
                'title' => $this->campaign->title,
                'body' => $this->campaign->text,
                'sound' => 'default',
                'mutable_content' => true
            ]
        )->data(
            [
                'image' => $this->campaign->getThumbnailUrl(),
                'campaignId' => $this->campaign->id,
                'deeplink' => $link,
            ]
        )->priority(FcmMessage::PRIORITY_NORMAL);

        return $message;
    }
}
