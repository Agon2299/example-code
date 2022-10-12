<?php


namespace App\Entities\Shop\Resources;


use App\Base\BaseResource;
use App\Entities\Offer\Resources\ListOfferResource;
use App\Entities\Promotion\Resources\PromotionShopResource;
use App\Entities\Shop\Models\Shop;

/**
 * Class NewsResource
 * @package App\Entities\News\Resources
 * @mixin Shop
 */
class SingleShopWithoutOffersResource extends BaseResource
{
    public function toArray($request)
    {
        $now = now();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo_url' => $this->getThumbnailUrl(),
            'site_url' => $this->site_url,
            'sitemap' => $this->map,
            'floor' => $this->floor,
            'description' => htmlspecialchars_decode(html_entity_decode($this->description), ENT_COMPAT | ENT_HTML5),
            'promotions' => PromotionShopResource::collection(
                $this->promotions->where('publish_start_at', '<=', $now)->where('end_at', '>=', $now)
            ),
            'images' => $this->getImagesUrl(),
            'cashback' => $this->cashback,
        ];
    }
}
