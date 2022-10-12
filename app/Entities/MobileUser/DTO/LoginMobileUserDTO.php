<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class LoginMobileUserDTO extends DataTransferObject
{
    public string $phone;
}
