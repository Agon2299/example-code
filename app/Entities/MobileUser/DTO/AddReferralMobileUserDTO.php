<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class AddReferralMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $referralCode;
    public string $token;
}
