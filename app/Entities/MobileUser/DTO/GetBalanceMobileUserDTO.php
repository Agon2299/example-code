<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class GetBalanceMobileUserDTO extends DataTransferObject
{
    public string $token;
}
