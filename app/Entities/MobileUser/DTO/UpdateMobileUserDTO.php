<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class UpdateMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public array $data;
    public string $token;
}
