<?php

namespace App\Entities\MobileDevice\Listeners;

use App\Entities\CampaignStatus\Models\CampaignStatus;
use Illuminate\Notifications\Events\NotificationSent;

class SendCampaignMobileDevice
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
    public function handle(NotificationSent $event)
    {
        if ($event->channel === 'fcm') {
            $status = $event->response[0] ?? null;

            CampaignStatus::create([
                'mobile_device_id' => $event->notifiable->id,
                'campaign_id' => $event->notification->campaign->id,
                'status' => !is_null($status) && $status['success'] === 1 ? 'sent' : 'not sent',
            ]);

            file_put_contents(
                storage_path('/log-send-' . date('Y-m-d') . '.txt'),
                date('Y-m-d H:i:s') . ' Notification: ' . json_encode($event, JSON_UNESCAPED_UNICODE) . "\n",
                FILE_APPEND
            );
        }
    }
}
