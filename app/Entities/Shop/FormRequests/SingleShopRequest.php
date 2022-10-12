<?php


namespace App\Entities\Shop\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Shop\DTO\SingleShopDTO;

class SingleShopRequest extends BaseFormRequest
{

    public function requestToDto(): SingleShopDTO
    {
        return new SingleShopDTO([
            'shopId' => $this->route('shopId'),
        ]);
    }
}
