<?php

namespace App\Entities\Transaction\Events;

use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MobileUser $mobileUserFrom;
    public MobileUser $mobileUserTo;
    public string $referralType;
    public int $changeBalance;

    /**
     * Create a new event instance.
     *
     * @param MobileUser $mobileUserFrom
     * @param $objectTo
     * @param $referralType
     * @param $changeBalance
     */
    public function __construct(MobileUser $mobileUserFrom, $objectTo, $referralType, $changeBalance)
    {
        $this->mobileUserFrom = $mobileUserFrom;
        $this->objectTo = $objectTo;
        $this->referralType = $referralType;
        $this->changeBalance = $changeBalance;
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
