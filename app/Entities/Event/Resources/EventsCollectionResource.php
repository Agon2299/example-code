<?php


namespace App\Entities\Event\Resources;


use App\Base\BaseCollectionResource;
use App\Entities\Event\Models\Event;

/**
 * Class EventsCollectionResource
 * @package App\Entities\Event\Resources
 * @mixin Event
 */
class EventsCollectionResource extends BaseCollectionResource
{
    public $collects = EventResource::class;

    public function toArray($request)
    {
        $mobileUserId = $request->query('mobileUserId');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl(),
            'date' => $this->start_at,
            'participation' => null,
            'cost' => $this->cost,
            'event_purchased' => !is_null($mobileUserId) ? $this->mobileUsers()->where('id', $mobileUserId)->exists() : false,
        ];
    }
}
