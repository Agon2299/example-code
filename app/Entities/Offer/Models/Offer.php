<?php

namespace App\Entities\Offer\Models;

use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use App\Entities\ActivatedOffer\Models\ActivatedOffer;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\Promotion\Models\Promotion;
use App\Entities\Shop\Models\Shop;
use App\Entities\Transaction\Models\Transaction;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;

class Offer extends BaseModel implements HasMedia
{
    use Filterable;
    use HasThumbnail;

    protected $fillable = [
        'shop_id',
        'name',
        'disclaimer',
        'description',
        'confirmation_conditions',
        'conditions_receiving',
        'type',
        'cost',
        'active_welcome_offer',
        'on_home',
        'start_at',
        'end_at',
        'count',
        'priority'
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    public function getTypeModelAttribute()
    {
        return 'Offer';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function mobileUsers(): BelongsToMany
    {
        return $this->belongsToMany(MobileUser::class);
    }

    public function mobileUsersWithPivot(): BelongsToMany
    {
        return $this->belongsToMany(MobileUser::class)->withPivot(['mobile_user_id']);
    }

    public function promotion(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function activatedOffers(): HasMany
    {
        return $this->hasMany(ActivatedOffer::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionstable');
    }

    public function getTypeModelCampaignAttribute()
    {
        return 'offers';
    }
}
