<?php


namespace App\Entities\Promotion\Resources;


use App\Base\BaseResource;
use App\Entities\Promotion\Models\Promotion;

/**
 * Class PromotionsResource
 * @package App\Entities\Promotion\Resources
 * @mixin Promotion
 */
class PromotionShopResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl(),
            'disclaimer' => $this->disclaimer,
            'start_date' => $this->start_at->format('U'),
            'finish_date' => $this->end_at->format('U'),
        ];
    }
}
