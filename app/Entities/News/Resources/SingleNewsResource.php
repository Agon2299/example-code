<?php


namespace App\Entities\News\Resources;


use App\Base\BaseResource;
use App\Entities\News\Models\News;
use App\Entities\Promotion\Models\Promotion;

/**
 * Class NewsResource
 * @package App\Entities\News\Resources
 * @mixin News
 */
class SingleNewsResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo_url' => $this->getThumbnailUrl(),
            'content' => $this->content,
            'publish_date' => $this->publish_start_at->format('U'),
            'sitemap' => $this->getSitemapLinkCustomAttribute(),
        ];
    }
}
