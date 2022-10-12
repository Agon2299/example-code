<?php


namespace App\Entities\Offer\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Offer\DTO\OfferListDTO;
use App\Entities\Shop\DTO\ShopListDTO;

class OfferListRequest extends BaseFormRequest
{

    public function requestToDto(): OfferListDTO
    {
        return new OfferListDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'onHome' => $this->query('on_home'),
            'mobileUserId' => $this->query('mobileUserId'),
        ]);
    }
}
