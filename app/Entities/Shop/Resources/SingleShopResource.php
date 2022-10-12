<?php


namespace App\Entities\Shop\Resources;


use App\Base\BaseResource;
use App\Entities\Offer\Resources\ListOfferResource;
use App\Entities\Promotion\Resources\PromotionShopResource;
use App\Entities\Shop\Models\Shop;
use Illuminate\Support\Facades\DB;

/**
 * Class NewsResource
 * @package App\Entities\News\Resources
 * @mixin Shop
 */
class SingleShopResource extends BaseResource
{
    public function toArray($request)
    {
        $now = now();
        $mobileUserId = $request->query('mobileUserId');
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
            'offers' => ListOfferResource::collection(
                $this->offers()
                    ->where('start_at', '<=', $now)
                    ->where('end_at', '>=', $now)
                    ->where('count', '>', function ($query) {
                        $query->select(DB::raw('count(*)'))
                            ->from('transactions')
                            ->whereRaw('transactions.transactionstable_id = offers.id and transactions.deleted_at is NULL');
                    })
                    ->when($mobileUserId, static function ($query) use ($mobileUserId) {
                        return $query->where(static function ($query) use ($mobileUserId) {
                            return $query->where(static function ($query) use ($mobileUserId) {
                                return $query->whereNotExists(static function ($query) use ($mobileUserId) {
                                    $query->select(DB::raw(1))
                                        ->from('activated_offers')
                                        ->whereRaw('activated_offers.offer_id = offers.id')
                                        ->where('activated_offers.mobile_user_id', $mobileUserId);
                                })->where('type', 'welcome_offer');
                            })->orWhere('type', '<>', 'welcome_offer');
                        });
                    })
                    ->get()
            ),
        ];
    }
}
