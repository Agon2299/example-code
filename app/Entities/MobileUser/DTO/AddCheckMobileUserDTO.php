<?php


namespace App\Entities\MobileUser\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class AddCheckMobileUserDTO extends DataTransferObject
{
    public string $mobileUserId;
    public string $token;
    public $amount;
    public $fss;
    public $number;
    public $fiscalsign;
    public $purchaseDate;
    public $cashback;
}
