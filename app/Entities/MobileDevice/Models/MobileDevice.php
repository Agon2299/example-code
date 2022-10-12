<?php

namespace App\Entities\MobileDevice\Models;

use App\Base\BaseUserModel;
use App\Entities\AppFeedback\Models\AppFeedback;
use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class MobileDevice extends BaseUserModel
{
    use Notifiable;

    protected $fillable = [
        'install_app',
        'os',
        'version_os',
        'model',
        'mac_address',
        'idfa',
        'enable_push_promotion',
        'enable_push_event',
        'enable_push_children',
    ];

    protected $dates = [
        'install_app',
    ];

    public function routeNotificationForFcm($notification)
    {
        return $this->token;
    }

    public function mobileUser(): BelongsTo
    {
        return $this->belongsTo(MobileUser::class);
    }

    public function appFeedback(): HasOne
    {
        return $this->hasOne(AppFeedback::class);
    }
}
