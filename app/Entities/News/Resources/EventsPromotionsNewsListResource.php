<?php


namespace App\Entities\News\Resources;

use App\Base\BaseResource;


class EventsPromotionsNewsListResource extends BaseResource
{
    public function toArray($request)
    {
        $mobileUserId = $request->query('mobileUserId');

        return [
            'id' => $this->id,
            'type' => $this->typeModel,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl(),
            'on_home' => $this->on_home,
            'on_main' => $this->on_main,
            'priority' => $this->priority,
            'purchased' => !is_null($mobileUserId) && !in_array($this->typeModel, ['news', 'promotion'])
                ? $this->mobileUsers()->where('id', $mobileUserId)->exists()
                : false
        ];
    }
}
