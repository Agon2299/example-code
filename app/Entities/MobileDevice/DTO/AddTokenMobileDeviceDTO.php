<?php


namespace App\Entities\MobileDevice\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class AddTokenMobileDeviceDTO extends DataTransferObject
{
    public string $token;
    public string $mobileDeviceId;
}
