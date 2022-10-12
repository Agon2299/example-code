<?php


namespace App\Entities\Offer\Resources;

use App\Base\BaseResource;
use App\Entities\Category\Resources\CategoryResource;
use App\Entities\Shop\Resources\SingleShopResource;
use App\Entities\Shop\Resources\SingleShopWithoutOffersResource;
use App\Entities\Subcategory\Resources\SubcategoryListResource;


class ListOfferResource extends BaseResource
{
    public function toArray($request)
    {
        $mobileUserId = $request->query('mobileUserId');
        $offer_purchased = !is_null($mobileUserId) ? $this->mobileUsers()->where('id', $mobileUserId)->exists() : false;
        $exists_activated = $this->activatedOffers()->where([
            ['mobile_user_id', $mobileUserId],
            ['offer_id', $this->id],
            ['type', 'activated'],
        ])->exists();

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
            'on_home' => $this->on_home,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'created_at' => $this->created_at,
            'image' => $this->getThumbnailUrl(),
            'offer_purchased' => $offer_purchased,
            'offer_activated_for_multiple' => $this->type === 'multiple' && $offer_purchased && $exists_activated,
        ];
    }
}
