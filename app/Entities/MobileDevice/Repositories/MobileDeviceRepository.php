<?php


namespace App\Entities\MobileDevice\Repositories;


use App\Base\BaseRepository;
use App\Entities\MobileDevice\Models\MobileDevice;


class MobileDeviceRepository extends BaseRepository
{
    public function store($data)
    {
        return MobileDevice::create($data);
    }

    public function updateUserId($mobileUser, $mobileDeviceId)
    {
        $mobileDevices = MobileDevice::where('mobile_user_id', $mobileUser->id)->get();
        if (count($mobileDevices)) {
            foreach ($mobileDevices as $mobileDevice) {
                $mobileDevice->mobile_user_id = null;
                $mobileDevice->save();
            }
        }

        $mobileDevice = MobileDevice::find($mobileDeviceId);
        $mobileDevice->mobile_user_id = $mobileUser->id;
        $mobileDevice->save();

        return $mobileDevice;
    }

    public function logout($mobileDeviceId)
    {
        $mobileDevice = MobileDevice::find($mobileDeviceId);
        $mobileDevice->mobile_user_id = null;
        $mobileDevice->save();

        return $mobileDevice;
    }

    public function addToken($mobileDeviceId, $token)
    {
        $mobileDevice = MobileDevice::find($mobileDeviceId);
        $mobileDevice->token = $token;
        $mobileDevice->save();

        return $mobileDevice;
    }

    public function updateSettings($mobileDeviceId, $enablePushEvent, $enablePushChildren, $enablePushPromotion)
    {
        $newSettings = [];
        if (!is_null($enablePushEvent)) {
            $newSettings['enable_push_event'] = $enablePushEvent;
        }

        if (!is_null($enablePushChildren)) {
            $newSettings['enable_push_children'] = $enablePushChildren;
        }

        if (!is_null($enablePushPromotion)) {
            $newSettings['enable_push_promotion'] = $enablePushPromotion;
        }

        $mobileDevice = MobileDevice::find($mobileDeviceId);
        if (!empty($newSettings)) {
            $mobileDevice->update($newSettings);
        }

        return $mobileDevice;
    }

    public function getSingle($mobileDeviceId)
    {
        return MobileDevice::find($mobileDeviceId) ?: [];
    }
}
