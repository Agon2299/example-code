<?php


namespace App\Entities\Event\Resources;


use App\Base\BaseResource;
use App\Entities\Event\Models\Event;

/**
 * Class EventsResource
 * @package App\Entities\Event\Resources
 * @mixin Event
 */
class EventResource extends BaseResource
{
    public function toArray($request)
    {
        $mobileUserId = $request->query('mobileUserId');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl(),
            'content' => $this->content,
            'date' => $this->when($this->start_at, [
                'start' => optional($this->start_at)->format('U'),
                'finish' => optional($this->end_at)->format('U'),
            ]),
            'sitemap' => $this->shop->map ?? null,
            'cost' => $this->cost,
            'event_purchased' => !is_null($mobileUserId) ? $this->mobileUsers()->where('id', $mobileUserId)->exists() : false,
        ];
    }
}
