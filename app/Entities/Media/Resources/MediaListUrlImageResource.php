<?php


namespace App\Entities\Media\Resources;

use App\Base\BaseResource;


class MediaListUrlImageResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'url' => $this->getUrl(),
        ];
    }
}
