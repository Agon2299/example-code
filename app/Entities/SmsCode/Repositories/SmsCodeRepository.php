<?php


namespace App\Entities\SmsCode\Repositories;


use App\Base\BaseRepository;
use App\Entities\SmsCode\Models\SmsCode;


class SmsCodeRepository extends BaseRepository
{
    public function createOrUpdate($mobileUserId, $codeSms)
    {
        $smsCode = SmsCode::where('mobile_user_id', $mobileUserId)->first();
        if (!$smsCode) {
            return SmsCode::create([
                'mobile_user_id' => $mobileUserId,
                'code' => $codeSms
            ]);
        } else {
            $smsCode->update([
                'code' => $codeSms
            ]);
        }

        return $smsCode;
    }
}
