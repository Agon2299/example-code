<?php


namespace App\Entities\Check\DTO;


use App\Base\BaseDataTransferObject;

class CheckListDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public string $token;
    public string $mobileUserId;
}
