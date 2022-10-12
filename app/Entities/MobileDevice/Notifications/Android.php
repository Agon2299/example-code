<?php

namespace App\Entities\MobileDevice\Notifications;

use App\Entities\Campaign\Models\Campaign;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Notifications\Notification;

class Android extends Notification
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
            'campaignId' => $this->campaign->id,
            'image' => $this->campaign->getThumbnailUrl() ?? null,
            'deeplink' => $link,
        ];

        $this->data = $data;

        $message->data($data)->priority(FcmMessage::PRIORITY_NORMAL);

        return $message;
    }
}
