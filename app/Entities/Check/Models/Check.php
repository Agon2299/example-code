<?php


namespace App\Entities\Check\Models;


use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use App\Entities\Cashbox\Models\Cashbox;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\Shop\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
class Check extends BaseModel
{
    protected $fillable = [
        'id',
        'mobile_user_id',
        'cashbox_id',
        'number',
        'fiscalsign',
        'amount',
        'shop_id',
        'purchase_date'
    ];

    protected $dates = [
        'purchase_date',
        'created_at',
        'updated_at',
    ];

    public function getTypeModelAttribute()
    {
        return 'check';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionstable');
    }

    public function mobileUser(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class);
    }

    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }

    public function getIdTransactionAttribute() {
        $id = $this->transactions->first()->id ?? null;
        if (!$id) {
            return $id;
        }

        return '<a href="/admin-panel/resources/transaction-nova-resources/' . $id . '" class="no-underline dim text-primary font-bold">' . $id . '</a>';
    }

    public function getAmountTransactionAttribute() {
        return $this->transactions->first()->change_balance ?? null;
    }
}
