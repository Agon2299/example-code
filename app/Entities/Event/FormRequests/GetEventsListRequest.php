<?php


namespace App\Entities\Event\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Event\DTO\EventsListDTO;
use App\Entities\Event\DTO\SingleEventDTO;
use App\Entities\News\DTO\NewsListDTO;

/**
 * Class GetSingleEventsRequest
 * @package App\Entities\Event\FormRequests
 */
class GetEventsListRequest extends BaseFormRequest
{
    public function requestToDto(): EventsListDTO
    {
        return new EventsListDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'onHome' => $this->query('on_home'),
            'mobileUserId' => $this->query('mobileUserId'),
        ]);
    }
}
