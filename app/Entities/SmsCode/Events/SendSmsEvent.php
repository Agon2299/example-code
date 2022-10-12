<?php

namespace App\Entities\SmsCode\Events;

use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendSmsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MobileUser $mobileUser;

    /**
     * Create a new event instance.
     *
     * @param MobileUser $mobileUser
     */
    public function __construct(MobileUser $mobileUser)
    {
        $this->mobileUser = $mobileUser;
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
