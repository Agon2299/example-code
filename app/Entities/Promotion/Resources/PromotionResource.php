<?php


namespace App\Entities\Promotion\Resources;


use App\Base\BaseResource;
use App\Entities\Promotion\Models\Promotion;

/**
 * Class PromotionsResource
 * @package App\Entities\Promotion\Resources
 * @mixin Promotion
 */
class PromotionResource extends BaseResource
{
    public function toArray($request)
    {
        $now = now();
        $offer = $this->offer;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl() ?: null,
            'disclaimer' => $this->disclaimer,
            'start_date' => $this->start_at->format('U'),
            'finish_date' => $this->end_at->format('U'),
            'content' => $this->content,
            'sitemap' => $this->shop->map ?? '',
            'offer_id' => $offer && $offer->start_at <= $now && $offer->end_at >= $now ? $offer->id : '',
            'on_home' => $this->on_home,
            'on_main' => $this->on_main
        ];
    }
}
