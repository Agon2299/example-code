<?php


namespace App\Entities\Check\Resources;


use App\Base\BaseResource;
use App\Entities\Event\Models\Event;

/**
 * Class EventsResource
 * @package App\Entities\Event\Resources
 * @mixin Event
 */
class CheckResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cashbox' => $this->cashbox,
            'number' => $this->number,
            'fiscalsign' => $this->fiscalsign,
            'amount' => $this->amount,
            'shop' => $this->shop,
            'purchase_date' => $this->purchase_date,
            'add_check_date' => $this->created_at
        ];
    }
}
