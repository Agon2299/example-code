<?php

namespace App\Entities\Transaction\Models;

use App\Base\BaseModel;
use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'mobile_user_id',
        'change_balance',
        'type',
    ];

    public function mobileUser(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class);
    }

    public function getTypeTransactionAttribute(): ?string
    {
        switch ($this->type) {
            case 'referral_activated':
                return 'Реферальная программа (зашли по коду)';
            case 'referral_activation':
                return 'Реферальная программа (зашел по коду)';
            case 'buy_event':
                return 'Покупка мероприятия';
            case 'buy_offer':
                return 'Покупка предложения';
            case 'check':
                return 'Скан чека';
            case 'survey':
                return 'Опрос';
            case 'registration':
                return 'Регистрация';
            case 'accrual_admin':
                return 'Админ начисление';
            case 'deductions_admin':
                return 'Админ списание';
        }

        return 'Не известно';
    }

    public function getSumCheckAttribute()
    {
        return $this->transactionstable->amount ?? null;
    }

    /**
     * Get the owning commentable model.
     */
    public function transactionstable(): MorphTo
    {
        return $this->morphTo();
    }
}
