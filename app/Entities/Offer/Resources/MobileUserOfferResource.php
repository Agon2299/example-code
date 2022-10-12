<?php


namespace App\Entities\Offer\Resources;


use App\Base\BaseResource;
use App\Entities\Shop\Models\Shop;
use App\Entities\Shop\Resources\SingleShopResource;
use App\Entities\Shop\Resources\SingleShopWithoutOffersResource;

/**
 * Class NewsResource
 * @package App\Entities\News\Resources
 * @mixin Shop
 */
class MobileUserOfferResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop' => new SingleShopWithoutOffersResource($this->shop),
            'name' => $this->name,
            'disclaimer' => $this->disclaimer,
            'description' => $this->description,
            'confirmation_conditions' => $this->confirmation_conditions,
            'conditions_receiving' => $this->conditions_receiving,
            'type' => $this->type,
            'cost' => $this->cost,
            'count' => $this->count,
            'active_welcome_offer' => $this->active_welcome_offer,
            'on_home' => $this->on_home,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'created_at' => $this->created_at,
            'image' => $this->getThumbnailUrl(),
        ];
    }
}
