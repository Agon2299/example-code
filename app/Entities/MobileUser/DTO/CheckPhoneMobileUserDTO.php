<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class CheckPhoneMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $phone;
    public string $token;
}
