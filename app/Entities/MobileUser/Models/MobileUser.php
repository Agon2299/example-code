<?php

namespace App\Entities\MobileUser\Models;

use App\Base\BaseUserModel;
use App\Entities\ActivatedOffer\Models\ActivatedOffer;
use App\Entities\BanMobileUser\Models\BanMobileUser;
use App\Entities\Check\Models\Check;
use App\Entities\Event\Models\Event;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\Offer\Models\Offer;
use App\Entities\SearchQuery\Models\SearchQuery;
use App\Entities\SmsCode\Models\SmsCode;
use App\Entities\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Airlock\HasApiTokens;
use App\Entities\AppFeedback\Models\AppFeedback;

class MobileUser extends BaseUserModel
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'id',
        'mobile_id',
        'phone',
        'name',
        'email',
        'gender',
        'registration_by_promo_code',
        'referral_code',
        'enable_push_promotion',
        'enable_push_event',
        'enable_push_children',
        'finish_poll',
        'date_birth',
        'have_kids',
        'count_invited_users',
        'count_registered_checks',
        'total_amount_of_all_checks',
        'average_check',
        'points_balance',
        'total_points_accumulated',
        'total_points_spent',
        'count_offers_purchased',
        'maximum_purchased_offers',
        'app_evaluation',
        'remember_token',
        'created_at',
        'referral_user_id',
        'count_share',
    ];

    protected $dates = [
        'date_birth',
    ];

    public function getTypeModelAttribute()
    {
        return 'Mobile User';
    }

    public function routeNotificationForSmsru()
    {
        return $this->phone;
    }

    public function smsCode(): HasMany
    {
        return $this->hasMany(SmsCode::class);
    }

    public function mobileDevice(): HasOne
    {
        return $this->hasOne(MobileDevice::class);
    }

    public function appFeedback(): HasMany
    {
        return $this->hasMany(AppFeedback::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionstable');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class);
    }

    public function searchQueries(): HasMany
    {
        return $this->hasMany(SearchQuery::class);
    }

    public function transactionsMobileUser()
    {
        return $this->hasMany(Transaction::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    public function getEnablePushChildrenAttribute()
    {
        return $this->mobileDevice->enable_push_children ?? null;
    }

    public function getEnablePushEventAttribute()
    {
        return $this->mobileDevice->enable_push_event ?? null;
    }

    public function getEnablePushPromotionAttribute()
    {
        return $this->mobileDevice->enable_push_promotion ?? null;
    }

    public function getCustomMobileDeviceIdAttribute()
    {
        $mobileDevice = $this->mobileDevice;
        if ($mobileDevice) {
            return '<a href="/admin-panel/resources/mobile-device-nova-resources/' . $mobileDevice->id . '" class="no-underline dim text-primary font-bold">' . $mobileDevice->id . '</a>';
        }

        return null;
    }

    public function getEnablePushAllAttribute()
    {
        return $this->enable_push_promotion || $this->enable_push_event || $this->enable_push_children;
    }

    public function getDateInstallAttribute()
    {
        $mobileDevice = $this->mobileDevice;
        return $mobileDevice->install_app ?? null;
    }

    public function getExistsSurveyAttribute()
    {
        return $this->transactionsMobileUser()->where('type', 'survey')->exists();
    }

    public function getDateRegistrationAttribute()
    {
        $transaction = $this->transactionsMobileUser->where('type', 'registration')->first();
        return $transaction->created_at ?? null;
    }

    public function getCountReferralUserAttribute()
    {
        return $this->transactionsMobileUser()->where('type', 'referral_activated')->count();
    }

    public function getCountCheckAttribute(): int
    {
        return $this->checks()->count();
    }

    public function getAverageCheckAttribute()
    {
        return $this->countCheck > 0 ? round($this->total_amount_of_all_checks / $this->countCheck, 1) : null;
    }

    public function getCountOfferAttribute(): int
    {
        return $this->transactionsMobileUser()->where('type', 'buy_offer')->count();
    }

    public function getCountEventAttribute(): int
    {
        return $this->transactionsMobileUser()->where('type', 'buy_event')->count();
    }

    public function getAppEvaluationAttribute()
    {
        return $this->appFeedback()->latest()->first()->rating ?? null;
    }

    public function activatedOffers(): HasMany
    {
        return $this->hasMany(ActivatedOffer::class);
    }

    public function banMobileUser(): HasOne
    {
        return $this->hasOne(BanMobileUser::class);
    }

    public function getCustomStatusMobileUserAttribute()
    {
        $banUserMobile = $this->banMobileUser;

        if (!$banUserMobile) {
            return 'Активен';
        }

        if (!$banUserMobile->use_check && !$banUserMobile->use_login) {
            return 'Активен';
        }

        if ($banUserMobile->use_check && $banUserMobile->use_login) {
            return 'Полностью заблокирован';
        }

        if ($banUserMobile->use_check) {
            return 'Заблокировано сканирование чеков';
        }

        return 'Заблокирована авторизация';
    }
}
