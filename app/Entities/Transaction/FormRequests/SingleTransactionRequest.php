<?php


namespace App\Entities\Transaction\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Transaction\DTO\SingleTransactionDTO;

class SingleTransactionRequest extends BaseFormRequest
{

    public function requestToDto(): SingleTransactionDTO
    {
        return new SingleTransactionDTO([
            'shopId' => $this->route('shopId'),
        ]);
    }
}
