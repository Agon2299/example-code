<?php


namespace App\Entities\Category\Resources;

use App\Base\BaseResource;


class CategoryListResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_search' => $this->is_search,
            'slug' => $this->slug,
            'has_filter' => $this->has_filter,
        ];
    }
}
