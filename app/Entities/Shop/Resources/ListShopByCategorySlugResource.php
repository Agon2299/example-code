<?php


namespace App\Entities\Shop\Resources;

use App\Base\BaseResource;
use App\Entities\Category\Resources\CategoryResource;
use App\Entities\Subcategory\Resources\SubcategoryListResource;


class ListShopByCategorySlugResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo_url' => $this->getThumbnailUrl(),
            'site_url' => $this->site_url,
            'sitemap' => $this->map,
            'floor' => $this->floor,
            'description' => htmlspecialchars_decode(html_entity_decode($this->description), ENT_COMPAT | ENT_HTML5),
            'category' => CategoryResource::collection($this->categories),
            'subcategory' => SubcategoryListResource::collection($this->subcategories),
            'images' => $this->getImagesUrl(),
        ];
    }
}
