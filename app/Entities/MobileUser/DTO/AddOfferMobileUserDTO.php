<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class AddOfferMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $offerId;
    public string $token;
}
