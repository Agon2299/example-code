<?php


namespace App\Entities\Event\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Event\DTO\SingleEventDTO;

/**
 * Class GetSingleEventsRequest
 * @package App\Entities\Event\FormRequests
 */
class GetSingleEventRequest extends BaseFormRequest
{
    public function requestToDto(): SingleEventDTO
    {
        return new SingleEventDTO([
            'eventId' => $this->route('eventId'),
            'mobileUserId' => $this->query('mobileUserId'),
        ]);
    }
}
