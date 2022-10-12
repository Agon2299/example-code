<?php


namespace App\Entities\Check\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class SingleCheckDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $checkId;
    public string $token;
}
