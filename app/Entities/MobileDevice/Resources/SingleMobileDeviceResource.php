<?php


namespace App\Entities\MobileDevice\Resources;


use App\Base\BaseResource;

class SingleMobileDeviceResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id
        ];
    }
}
