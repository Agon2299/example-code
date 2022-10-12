<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class AddEventMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $eventId;
    public string $token;
}
