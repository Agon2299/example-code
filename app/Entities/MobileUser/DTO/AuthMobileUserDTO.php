<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class AuthMobileUserDTO extends DataTransferObject
{
    public string $smsCode;
    public string $phone;
    public string $mobileDeviceId;
}
