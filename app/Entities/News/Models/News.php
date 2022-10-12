<?php


namespace App\Entities\News\Models;


use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use Barryvdh\LaravelIdeHelper\Eloquent;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use App\Entities\Shop\Models\Shop;

/**
 * App\Entities\News\Models\News
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $publish_start_at
 * @property \Illuminate\Support\Carbon|null $publish_end_at
 * @property string $title
 * @property string $content
 * @property string|null $sitemap_link
 * @property string|null $logo_url
 * @property bool $on_main
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereOnMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News wherePublishEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News wherePublishStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereSitemapLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\News\Models\News whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read mixed $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Media\Models\Media[] $media
 * @property-read int|null $media_count
 */
class News extends BaseModel implements HasMedia
{
    use Filterable;
    use HasThumbnail;

    protected $dates = [
        'publish_start_at',
        'publish_end_at',
    ];

    public function getTypeModelAttribute() {
        return 'news';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function getSitemapLinkCustomAttribute() {
        return $this->shop->map ?? null;
    }

    public function getTypeModelCampaignAttribute()
    {
        return 'news';
    }
}
