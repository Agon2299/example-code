<?php


namespace App\Entities\Category\Resources;

use App\Base\BaseResource;


class CategoryResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
