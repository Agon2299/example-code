<?php


namespace App\Entities\Transaction\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Shop\DTO\ShopListDTO;
use App\Entities\Transaction\DTO\TransactionListDTO;

class TransactionListRequest extends BaseFormRequest
{

    public function requestToDto(): TransactionListDTO
    {
        return new TransactionListDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'mobileUserId' => $this->route('mobileUserID'),
            'token' => $this->header('token'),
        ]);
    }
}
