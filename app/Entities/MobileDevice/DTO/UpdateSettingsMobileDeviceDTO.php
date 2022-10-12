<?php


namespace App\Entities\MobileDevice\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class UpdateSettingsMobileDeviceDTO extends DataTransferObject
{
    public string $mobileDeviceId;
    public $enablePushPromotion;
    public $enablePushEvent;
    public $enablePushChildren;
}
