<?php


namespace App\Entities\Transaction\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class SingleTransactionDTO extends DataTransferObject
{
    public string $transactionId;
}
