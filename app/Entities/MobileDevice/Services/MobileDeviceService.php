<?php


namespace App\Entities\MobileDevice\Services;


use App\Base\BaseService;
use App\Entities\MobileDevice\DTO\AddTokenMobileDeviceDTO;
use App\Entities\MobileDevice\DTO\GetSettingsMobileDeviceDTO;
use App\Entities\MobileDevice\DTO\SingleMobileDeviceDTO;
use App\Entities\MobileDevice\DTO\StoreMobileDeviceDTO;
use App\Entities\MobileDevice\DTO\UpdateSettingsMobileDeviceDTO;
use App\Entities\MobileDevice\Repositories\MobileDeviceRepository;
use App\Entities\MobileDevice\Resources\SettingsMobileDeviceResource;
use App\Entities\MobileDevice\Resources\SingleMobileDeviceResource;


class MobileDeviceService extends BaseService
{
    private MobileDeviceRepository $mobileDeviceRepository;

    public function __construct(MobileDeviceRepository $mobileDeviceRepository)
    {
        $this->mobileDeviceRepository = $mobileDeviceRepository;
    }

    public function store(StoreMobileDeviceDTO $mobileDeviceDTO)
    {
        return new SingleMobileDeviceResource($this->mobileDeviceRepository->store($mobileDeviceDTO->data));
    }

    public function updateUserId($mobileUser, $mobileDeviceId)
    {
        return $this->mobileDeviceRepository->updateUserID($mobileUser, $mobileDeviceId);
    }

    public function logout($mobileDeviceId)
    {
        return $this->mobileDeviceRepository->logout($mobileDeviceId);
    }

    public function addToken(AddTokenMobileDeviceDTO $addTokenMobileDeviceDTO)
    {
        return $this->mobileDeviceRepository->addToken($addTokenMobileDeviceDTO->mobileDeviceId, $addTokenMobileDeviceDTO->token);
    }

    public function updateSettings(UpdateSettingsMobileDeviceDTO $updateSettingsMobileDeviceDTO)
    {
        return $this->mobileDeviceRepository->updateSettings(
            $updateSettingsMobileDeviceDTO->mobileDeviceId,
            $updateSettingsMobileDeviceDTO->enablePushEvent,
            $updateSettingsMobileDeviceDTO->enablePushChildren,
            $updateSettingsMobileDeviceDTO->enablePushPromotion,
        );
    }

    public function getSingle(SingleMobileDeviceDTO $getSettingsMobileDeviceDTO)
    {
        $mobileDevice = $this->mobileDeviceRepository->getSingle($getSettingsMobileDeviceDTO->mobileDeviceId);
        if (!empty($mobileDevice)) {
            return new SettingsMobileDeviceResource($mobileDevice);
        }

        return $mobileDevice;
    }
}
