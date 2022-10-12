<?php


namespace App\Entities\News\Resources;

use App\Base\BaseResource;


class NewsListResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->typeModel,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl(),
            'disclaimer' => $this->disclaimer,
            'priority' => $this->priority,
            'date' => $this->when($this->start_at, [
                'start' => optional($this->start_at)->format('U'),
                'finish' => optional($this->end_at)->format('U'),
            ]),
        ];
    }
}
