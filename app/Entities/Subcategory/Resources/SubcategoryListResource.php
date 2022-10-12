<?php


namespace App\Entities\Subcategory\Resources;

use App\Base\BaseResource;


class SubcategoryListResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
