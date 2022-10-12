<?php


namespace App\Entities\Transaction\DTO;


use App\Base\BaseDataTransferObject;

class TransactionListDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public string $mobileUserId;
    public string $token;
}
