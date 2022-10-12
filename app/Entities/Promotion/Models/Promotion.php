<?php


namespace App\Entities\Promotion\Models;


use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use App\Entities\Offer\Models\Offer;
use App\Entities\Shop\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

/**
 * App\Entities\Promotion\Models\Promotion
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $publish_start_at
 * @property \Illuminate\Support\Carbon $publish_end_at
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property string $title
 * @property string $content
 * @property string|null $disclaimer
 * @property string|null $sitemap_link
 * @property bool $on_main
 * @property bool $on_home
 * @property-read mixed $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Media\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereDisclaimer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereOnHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereOnMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion wherePublishEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion wherePublishStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereSitemapLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Promotion\Models\Promotion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Promotion extends BaseModel implements HasMedia
{
    use HasThumbnail;

    protected $dates = [
        'start_at',
        'end_at',
        'publish_start_at',
    ];

    public function getTypeModelAttribute()
    {
        return 'promotion';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function getSitemapLinkShopAttribute()
    {
        return $this->shop->map ?? null;
    }

    public function getTypeModelCampaignAttribute()
    {
        return 'promotions';
    }
}
