<?php


namespace App\Entities\Event\Models;


use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\Shop\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use App\Entities\Transaction\Models\Transaction;

/**
 * App\Entities\Event\Models\Event
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event query()
 * @mixin \Eloquent
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $content
 * @property string $start_at
 * @property string $end_at
 * @property string $publish_at
 * @property string|null $sitemap_link
 * @property int $cost
 * @property bool $on_main
 * @property bool $on_home
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereOnHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereOnMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereSitemapLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Event\Models\Event whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Media\Models\Media[] $media
 * @property-read int|null $media_count
 */
class Event extends BaseModel implements HasMedia
{
    use HasThumbnail;

    protected $dates = [
        'start_at',
        'end_at',
        'publish_at',
    ];

    public function getTypeModelAttribute()
    {
        return 'event';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionstable');
    }

    public function mobileUsers(): BelongsToMany
    {
        return $this->belongsToMany(MobileUser::class);
    }

    public function getSitemapLinkCustomAttribute() {
        return $this->shop->map ?? null;
    }

    public function getTypeModelCampaignAttribute()
    {
        return 'events';
    }
}
