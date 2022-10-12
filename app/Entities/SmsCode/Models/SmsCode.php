<?php

namespace App\Entities\SmsCode\Models;

use App\Base\BaseUserModel;
use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsCode extends BaseUserModel
{
    protected $fillable = [
        'mobile_user_id',
        'code',
    ];

    public function mobileUser(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class);
    }
}
