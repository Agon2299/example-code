<?php


namespace App\Entities\Check\Resources;


use App\Base\BaseCollectionResource;
use App\Entities\Event\Models\Event;

/**
 * Class EventsCollectionResource
 * @package App\Entities\Event\Resources
 * @mixin Event
 */
class CheckCollectionResource extends BaseCollectionResource
{
    public $collects = CheckResource::class;

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
