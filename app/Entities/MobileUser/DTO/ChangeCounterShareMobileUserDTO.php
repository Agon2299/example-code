<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class ChangeCounterShareMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $token;
}
