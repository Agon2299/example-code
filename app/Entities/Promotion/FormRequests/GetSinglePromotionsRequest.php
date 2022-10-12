<?php


namespace App\Entities\Promotion\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Promotion\DTO\SinglePromotionDTO;

/**
 * Class GetSinglePromotionsRequest
 * @package App\Entities\Promotion\FormRequests
 */
class GetSinglePromotionsRequest extends BaseFormRequest
{
    public function requestToDto(): SinglePromotionDTO
    {
        return new SinglePromotionDTO([
            'promotionId' => $this->route('promotionId')
        ]);
    }
}
