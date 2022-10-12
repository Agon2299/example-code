<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class GetOffersMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $token;
}
