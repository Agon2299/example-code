<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class LogoutMobileUserDTO extends DataTransferObject
{
    public string $token;
    public string $mobileDeviceId;
    public string $mobileUserId;
}
