<?php


namespace App\Entities\Promotion\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Promotion\DTO\GetPromotionsListDTO;

/**
 * Class GetSingleEventsRequest
 * @package App\Entities\Event\FormRequests
 */
class GetPromotionsListRequest extends BaseFormRequest
{
    public function requestToDto(): GetPromotionsListDTO
    {
        return new GetPromotionsListDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'onHome' => $this->query('on_home')
        ]);
    }
}
