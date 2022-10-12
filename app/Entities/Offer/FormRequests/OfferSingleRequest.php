<?php


namespace App\Entities\Offer\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Offer\DTO\OfferListDTO;
use App\Entities\Offer\DTO\OfferSingleDTO;
use App\Entities\Shop\DTO\ShopListDTO;

class OfferSingleRequest extends BaseFormRequest
{

    public function requestToDto(): OfferSingleDTO
    {
        return new OfferSingleDTO([
            'offerId' => $this->route('offerId'),
        ]);
    }
}
