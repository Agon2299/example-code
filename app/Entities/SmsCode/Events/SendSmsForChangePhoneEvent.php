<?php

namespace App\Entities\SmsCode\Events;

use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendSmsForChangePhoneEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MobileUser $mobileUser;
    public string $phone;

    /**
     * Create a new event instance.
     *
     * @param MobileUser $mobileUser
     * @param $phone
     */
    public function __construct(MobileUser $mobileUser, $phone)
    {
        $this->mobileUser = $mobileUser;
        $this->phone = $phone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
