<?php

namespace App\Entities\Offer\Events;

use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class OfferDetachMobileUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $object;
    public Request $request;

    /**
     * Create a new event instance.
     *
     */
    public function __construct($object)
    {
        dd($object->meta);
        $this->object = $object;
        $this->request = request();
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
