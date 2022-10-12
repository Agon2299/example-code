<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class ChangePhoneMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $phone;
    public string $token;
    public string $smsCode;
}
