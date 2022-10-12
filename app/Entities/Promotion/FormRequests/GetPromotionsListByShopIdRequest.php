<?php


namespace App\Entities\Promotion\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Promotion\DTO\GetPromotionsListByShopIdDTO;

/**
 * Class GetSingleEventsRequest
 * @package App\Entities\Event\FormRequests
 */
class GetPromotionsListByShopIdRequest extends BaseFormRequest
{
    public function requestToDto(): GetPromotionsListByShopIdDTO
    {
        return new GetPromotionsListByShopIdDTO([
            'shopId' => $this->route('shopId'),
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15)
        ]);
    }
}
